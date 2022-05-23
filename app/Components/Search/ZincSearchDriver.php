<?php

namespace App\Components\Search;

use App\Repositories\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * ZincSearchDriver
 *
 * https://docs.zincsearch.com/
 */
class ZincSearchDriver implements Driver
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * Index name
     *
     * @var string
     */
    private $index = '';

    /**
     * Basic Auth username
     * @var string
     */
    private $authUsername = '';

    /**
     * Basic Auth password
     *
     * @var string
     */
    private $authPassword = '';

    /**
     * 搜索类型
     *
     * @var string
     */
    private $searchType = 'matchphrase';

    public function __construct()
    {
        $this->client       = new Client([
            'base_uri' => config('wizard.search.drivers.zinc.server', 'http://localhost:4080'),
            'timeout'  => 3.0,
        ]);
        $this->index        = config('wizard.search.drivers.zinc.index', 'wizard');
        $this->authUsername = config('wizard.search.drivers.zinc.username');
        $this->authPassword = config('wizard.search.drivers.zinc.password');
        $this->searchType   = config('wizard.search.drivers.zinc.search_type');
    }

    /**
     * 鉴权
     *
     * @return array|null
     */
    private function auth(): ?array
    {
        if (empty($this->authUsername)) {
            return null;
        }

        return [$this->authUsername, $this->authPassword];
    }

    /**
     * 删除文档索引
     *
     * @param $id
     *
     * @return void
     */
    public function deleteIndex($id)
    {
        $this->client->delete(
            "/api/{$this->index}/_doc/{$id}",
            [
                'auth' => $this->auth(),
            ]
        );
    }


    /**
     * 同步索引
     *
     * @param Document $doc
     *
     * @return void
     * @throws \Exception
     */
    public function syncIndex(Document $doc)
    {
        $req = [
            'id'      => $doc->id,
            'type'    => $doc->type,
            'title'   => $doc->title,
            'content' => $doc->content,
        ];

        $resp = $this->client->put("/api/{$this->index}/_doc/{$doc->id}", [
            'json' => $req,
            'auth' => $this->auth(),
        ]);

        if ($resp->getStatusCode() !== 200) {
            throw new \Exception("sync document to server failed: " . $resp->getReasonPhrase() . ", response: " . $resp->getBody()->getContents());
        }
    }


    /**
     * 执行文档搜索
     *
     * @param string $keyword
     * @param int    $page
     * @param int    $perPage
     *
     * @return Result|null
     */
    public function search(string $keyword, int $page, int $perPage): ?Result
    {
        try {
            $resp = $this->client->post("/api/{$this->index}/_search", [
                'json' => [
                    'search_type' => $this->searchType,
                    'query'       => [
                        'term' => $keyword,
                    ],
                    'from'        => $page * $perPage - $perPage,
                    'max_results' => $perPage * 2,
                    '_source'     => ['id', 'type'],
                ],
                'auth' => $this->auth(),
            ]);

            if ($resp->getStatusCode() !== 200) {
                return null;
            }

            $respBody = json_decode($resp->getBody()->getContents(), true);

            if (empty($respBody['error'])) {
                $sortIds = collect($respBody['hits']['hits'] ?? [])->map(function ($doc) {
                    return $doc['_id'];
                })->toArray();

                return new Result(
                    array_slice($sortIds, 0, $perPage),
                    [$keyword],
                    $respBody['hits']['total']['value'] ?? null
                );
            }

            return null;
        } catch (\Exception $ex) {
            Log::error('search failed', ['message' => $ex->getMessage()]);
        }

        return null;
    }
}