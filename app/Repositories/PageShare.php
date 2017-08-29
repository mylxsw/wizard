<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Repositories;

use Carbon\Carbon;

/**
 * Class PageShare
 *
 * @property string  $code
 * @property integer $project_id
 * @property integer $page_id
 * @property integer $user_id
 * @property Carbon  $expired_at
 *
 * @package App\Repositories
 */
class PageShare extends Repository
{
    protected $table = 'wz_page_share';
    protected $fillable
        = [
            'code',
            'project_id',
            'page_id',
            'user_id',
            'expired_at',
        ];
}