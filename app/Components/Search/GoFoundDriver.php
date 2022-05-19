<?php

namespace App\Components\Search;

use App\Repositories\Document;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoFoundDriver implements Driver
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * Database
     *
     * @var string
     */
    private $database = '';

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

    public function __construct()
    {
        $this->client       = new Client([
            'base_uri' => config('wizard.search.drivers.gofound.server', 'http://localhost:5678'),
            'timeout'  => 3.0,
        ]);
        $this->database     = config('wizard.search.drivers.gofound.database', 'default');
        $this->authUsername = config('wizard.search.drivers.gofound.username');
        $this->authPassword = config('wizard.search.drivers.gofound.password');
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

        return ['username' => $this->authUsername, 'password' => $this->authPassword];
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
        $this->client->post(
            "/api/index/remove?database={$this->database}",
            [
                'json' => ['id' => $id],
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
            'id'       => $doc->id,
            'text'     => $doc->title . '\n\n' . $doc->content,
            'document' => [
                'id'         => $doc->id,
                'project_id' => $doc->project_id,
            ],
        ];

        $resp = $this->client->post("/api/index?database={$this->database}", [
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
            $resp = $this->client->post("/api/query?database={$this->database}", [
                'json' => [
                    'query' => $keyword,
                    'page'  => $page,
                    'limit' => $perPage * 2,
                ],
                'auth' => $this->auth(),
            ]);

            if ($resp->getStatusCode() !== 200) {
                return null;
            }

            $respBody = json_decode($resp->getBody()->getContents(), true);

            Log::info('search-request', $respBody);

            if ($respBody['state']) {
                $sortIds = collect($respBody['data']['documents'] ?? [])->map(function ($doc) {
                    return $doc['id'];
                })->toArray();

                return new Result(array_slice($sortIds, 0, $perPage), $respBody['data']['words'] ?? []);
            }

            return null;
        } catch (\Exception $ex) {
            Log::error('search failed', ['message' => $ex->getMessage()]);
        }

        return null;
    }
}