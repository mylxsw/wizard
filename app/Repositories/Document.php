<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Page
 *
 * @property integer $id
 * @property integer $pid
 * @property string  $title
 * @property string  $description
 * @property string  $content
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $last_modified_uid
 * @property integer $history_id
 * @property integer $type
 * @property integer $status
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * @package App\Repositories
 */
class Document extends Repository
{

    use SoftDeletes;

    const TYPE_DOC     = 1;
    const TYPE_SWAGGER = 2;

    protected $table = 'wz_pages';
    protected $fillable
        = [
            'pid',
            'title',
            'description',
            'content',
            'project_id',
            'user_id',
            'last_modified_uid',
            'history_id',
            'type',
            'status',
        ];

    public $dates = ['deleted_at'];

    /**
     * 文档恢复
     *
     * @param Document        $document
     * @param DocumentHistory $history
     *
     * @return Document
     */
    public static function recover(Document $document, DocumentHistory $history): Document
    {
        $document->pid               = $history->pid;
        $document->title             = $history->title;
        $document->description       = $history->description;
        $document->content           = $history->content;
        $document->last_modified_uid = $history->operator_id;
        $document->type              = $history->type;
        $document->status            = $history->status;

        $document->save();

        return $document;
    }

    /**
     * 所属的项目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * 页面所属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 最后修改页面的用户ID
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastModifiedUser()
    {
        return $this->belongsTo(User::class, 'last_modified_uid', 'id');
    }

    /**
     * 上级页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentPage()
    {
        return $this->belongsTo(self::class, 'pid', 'id');
    }

    /**
     * 子页面
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subPages()
    {
        return $this->hasMany(self::class, 'pid', 'id');
    }

    /**
     * 文档下的评论
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'page_id', 'id');
    }

    /**
     * 页面下所有的附件
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'page_id', 'id');
    }

    /**
     * 页面的标签
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'wz_page_tag', 'page_id', 'tag_id');
    }
}