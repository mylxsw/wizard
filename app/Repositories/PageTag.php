<?php

namespace App\Repositories;

/**
 * Class Tag
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $tag_id
 *
 * @package App\Repositories
 */
class PageTag extends Repository
{
    protected $table = 'wz_page_tag';
    public $timestamps = false;
    protected $fillable = ['page_id', 'tag_id'];
}