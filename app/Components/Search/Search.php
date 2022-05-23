<?php

namespace App\Components\Search;

use App\Repositories\Document;
use Illuminate\Support\Facades\Log;

class Search implements Driver
{
    private $driver = null;

    /**
     * 搜索驱动实例
     *
     * @var Driver
     */
    private static $instance;

    /**
     * @param null $driver
     */
    public function __construct($driver) { $this->driver = $driver; }

    /**
     * 获取搜索实现驱动实例
     *
     * @return Driver
     */
    public static function get(): Driver
    {
        if (is_null(self::$instance)) {
            $driverName     = config('wizard.search.driver');
            self::$instance = new self(new $driverName());
        }

        return self::$instance;
    }

    /**
     * 删除索引
     *
     * @param $id
     *
     * @return void
     */
    public function deleteIndex($id)
    {
        try {
            $this->driver->deleteIndex($id);
        } catch (\Exception $ex) {
            Log::error("search: delete index for documents failed", [
                'id'      => $id,
                'message' => $ex->getMessage(),
                'code'    => $ex->getCode(),
                'pos'     => "{$ex->getFile()}:{$ex->getLine()}",
            ]);
        }
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
        try {
            $this->driver->syncIndex($doc);
        } catch (\Exception $ex) {
            Log::error("search: sync index for documents failed", [
                'id'      => $doc->id,
                'message' => $ex->getMessage(),
                'code'    => $ex->getCode(),
                'pos'     => "{$ex->getFile()}:{$ex->getLine()}",
            ]);
        }
    }

    /**
     * 关键词搜索
     *
     * @param string $keyword
     * @param int    $page
     * @param int    $perPage
     *
     * @return Result|null
     */
    public function search(string $keyword, int $page, int $perPage): ?Result
    {
        return $this->driver->search($keyword, $page, $perPage);
    }
}