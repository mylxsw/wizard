<?php

namespace App\Components\Search;

use App\Repositories\Document;

/**
 * 基于数据库的搜索
 */
class NullDriver implements Driver
{

    public function deleteIndex($id)
    {
        // TODO: Implement deleteIndex() method.
    }

    public function syncIndex(Document $doc)
    {
        // TODO: Implement syncIndex() method.
    }

    public function search(string $keyword, int $page, int $perPage): ?Result
    {
        return null;
    }
}