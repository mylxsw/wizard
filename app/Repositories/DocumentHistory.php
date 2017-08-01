<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;

/**
 * Class DocumentHistory
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $pid
 * @property string  $title
 * @property string  $description
 * @property string  $content
 * @property integer $project_id
 * @property integer $user_id
 * @property string  $type
 * @property string  $status
 * @property integer $operator_id
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @package App\Repositories
 */
class DocumentHistory extends Repository
{
    protected $table = 'wz_page_histories';
    protected $fillable
        = [
            'page_id',
            'pid',
            'title',
            'description',
            'content',
            'project_id',
            'user_id',
            'type',
            'status',
            'operator_id',
        ];

    /**
     * 记录文档历史
     *
     * @param Document $document
     */
    public static function write(Document $document)
    {
        static::create(array_only(
                $document->toArray(),
                (new static)->fillable) + [
                'operator_id' => $document->last_modified_uid,
                'page_id'     => $document->id,
            ]
        );
    }
}