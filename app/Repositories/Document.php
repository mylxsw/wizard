<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;

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
 * @property integer $type
 * @property integer $status
 * @property string  $created_at
 * @property string  $updated_at
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
            'type',
            'status',
        ];

    public $dates = ['deleted_at'];

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
}