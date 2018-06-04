<?php

namespace App\Repositories;

/**
 * Class Tag
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $tag_id
 * @package App\Repositories
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageTag wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageTag whereTagId($value)
 */
class PageTag extends Repository
{
    protected $table = 'wz_page_tag';
    public $timestamps = false;
    protected $fillable = ['page_id', 'tag_id'];
}