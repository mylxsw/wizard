<?php

namespace App\Components\Search;

/**
 * 搜索结果
 */
class Result
{
    /**
     * 文档 ID 列表
     *
     * @var array
     */
    public $ids;

    /**
     * 搜索关键字列表
     *
     * @var array
     */
    public $words;

    /**
     * @param array $ids
     * @param array $words
     */
    public function __construct(array $ids, array $words)
    {
        $this->ids   = $ids;
        $this->words = $words;
    }
}