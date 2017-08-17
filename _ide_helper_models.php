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
 * App\Repositories\Comment
 *
 * @property-read \App\Repositories\Document $document
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withoutTrashed()
 */
	class Comment extends \Eloquent {}
}

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
 * @property integer $history_id
 * @property integer $type
 * @property integer $status
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Comment[] $comments
 * @property-read \App\Repositories\User $lastModifiedUser
 * @property-read \App\Repositories\Document $parentPage
 * @property-read \App\Repositories\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $subPages
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
 * @property-read \App\Repositories\User $operator
 * @property-read \App\Repositories\User $user
 */
	class DocumentHistory extends \Eloquent {}
}

namespace App\Repositories{
/**
 * Class Group
 *
 * @property integer $id
 * @property string  $name
 * @property integer $user_id
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \App\Repositories\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\User[] $users
 */
	class Group extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\OperationLogs
 *
 * @property mixed $context
 */
	class OperationLogs extends \Eloquent {}
}

namespace App\Repositories{
/**
 * Class PageShare
 *
 * @property string  $code
 * @property integer $project_id
 * @property integer $page_id
 * @property integer $user_id
 * @property Carbon  $expired_at
 * @package App\Repositories
 */
	class PageShare extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\Project
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Group[] $groups
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
 * @package App\Repositories
 */
	class Template extends \Eloquent {}
}

namespace App\Repositories{
/**
 * Class User
 *
 * @property integer $id
 * @property string  $name
 * @property string  $password
 * @property integer $role
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Group[] $groups
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 */
	class User extends \Eloquent {}
}

