<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Repositories;

use Carbon\Carbon;

/**
 * Class Template
 *
 * @property integer $id
 * @property string  $name
 * @property string  $description
 * @property string  $content
 * @property string  $user_id
 * @property string  $type
 * @property string  $status
 * @property string  $scope
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 *
 * @package App\Repositories
 */
class Template extends Repository
{
    const TYPE_SWAGGER = 1;
    const TYPE_DOC     = 2;

    const STATUS_NORMAL = 1;
    const STATUS_FORBID = 2;

    const SCOPE_GLOBAL  = 1;
    const SCOPE_PRIVATE = 2;

    protected $table = 'wz_templates';
    protected $fillable
        = [
            'name',
            'description',
            'content',
            'user_id',
            'type',
            'status',
            'scope',
        ];

    /**
     * 查询模板用于显示
     *
     * @param int       $type
     * @param User|null $user
     *
     * @return array
     */
    public static function queryForShow($type, User $user = null)
    {
        $templates = self::where('type', $type)
            ->where('status', self::STATUS_NORMAL)
            ->where(function ($query) use ($user) {
                if (!empty($user)) {
                    $query->where('user_id', $user->id);
                }

                $query->orWhere('scope', self::SCOPE_GLOBAL);
            })->get();

        return array_map(function (array $template) {
            return [
                'id'          => $template['id'],
                'name'        => $template['name'],
                'content'     => $template['content'],
                'user_id'     => $template['user_id'],
                'scope'       => $template['scope'],
                'description' => $template['description'],
                'default'     => false,
            ];
        }, $templates->toArray());
    }
}