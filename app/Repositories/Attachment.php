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
 * Class Attachment
 *
 * @property integer $id
 * @property string  $name
 * @property string  $path
 * @property integer $page_id
 * @property integer $project_id
 * @property integer $user_id
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $deleted_at
 * @package App\Repositories
 * @property-read \App\Repositories\Document $page
 * @property-read \App\Repositories\Project $project
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Attachment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Attachment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Attachment withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Attachment whereUserId($value)
 */
class Attachment extends Repository
{
    use SoftDeletes;

    protected $table = 'wz_attachments';
    protected $fillable
        = [
            'name',
            'path',
            'page_id',
            'project_id',
            'user_id'
        ];

    public $dates = ['deleted_at'];

    /**
     * 附件所属的文档
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Document::class, 'page_id', 'id');
    }

    /**
     * 附件所属的用户
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 附件所属的项目
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}