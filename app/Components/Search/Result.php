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
     * 总记录条数
     *
     * @var int|null
     */
    public $total;

    /**
     * @param array    $ids
     * @param array    $words
     * @param int|null $total
     */
    public function __construct(array $ids, array $words, int $total = null)
    {
        $this->ids   = $ids;
        $this->words = $words;
        $this->total = $total;
    }
}