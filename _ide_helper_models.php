<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Repositories{
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
 * @package App\Repositories
 * @property-read \App\Repositories\User $lastModifiedUser
 * @property-read \App\Repositories\Project $project
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document withoutTrashed()
 */
	class Document extends \Eloquent {}
}

namespace App\Repositories{
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
 * @package App\Repositories
 */
	class DocumentHistory extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\Project
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project withoutTrashed()
 */
	class Project extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 */
	class User extends \Eloquent {}
}

