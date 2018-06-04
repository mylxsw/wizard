<?php

namespace App\Repositories;

use Carbon\Carbon;

/**
 * Class Tag
 *
 * @property integer $id
 * @property string  $name
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Tag whereUpdatedAt($value)
 */
class Tag extends Repository
{
    protected $table = 'wz_tags';
    protected $fillable = ['name'];

    /**
     * 标签的页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pages()
    {
        return $this->belongsToMany(Document::class, 'wz_page_tag', 'tag_id', 'page_id');
    }
}