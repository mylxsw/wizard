<?php

namespace App\Components\Search;

use App\Repositories\Document;

class Search implements Driver
{
    /**
     * 搜索驱动实例
     *
     * @var Driver
     */
    private static $driver;

    /**
     * 获取搜索实现驱动实例
     *
     * @return Driver
     */
    public static function get(): Driver
    {
        if (is_null(self::$driver)) {
            $driverName   = config('wizard.search.driver');
            self::$driver = new $driverName();
        }

        return self::$driver;
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
        self::$driver->deleteIndex($id);
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
        self::$driver->syncIndex($doc);
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
        return self::$driver->search($keyword, $page, $perPage);
    }
}