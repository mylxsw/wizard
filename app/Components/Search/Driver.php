<?php

namespace App\Components\Search;

use App\Repositories\Document;

interface Driver
{
    /**
     * 删除文档索引
     *
     * @param $id
     *
     * @return void
     */
    public function deleteIndex($id);

    /**
     * 同步索引
     *
     * @param Document $doc
     *
     * @return void
     * @throws \Exception
     */
    public function syncIndex(Document $doc);

    /**
     * 执行文档搜索
     *
     * @param string $keyword
     * @param int    $page
     * @param int    $perPage
     *
     * @return Result|null
     */
    public function search(string $keyword, int $page, int $perPage): ?Result;
}