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
 * @property integer $history_id
 * @property integer $type
 * @property integer $status
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Attachment[] $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Comment[] $comments
 * @property-read \App\Repositories\User $lastModifiedUser
 * @property-read \App\Repositories\Document $parentPage
 * @property-read \App\Repositories\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $subPages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Tag[] $tags
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Document withoutTrashed()
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereLastModifiedUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Document whereUserId($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Repositories{
/**
 * 项目目录
 *
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 * @property-read \App\Repositories\User $user
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 项目目录名称
 * @property int $sort_level 排序，排序值越大越靠后
 * @property int $user_id 创建用户ID
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereSortLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Catalog whereUserId($value)
 */
	class Catalog extends \Eloquent {}
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
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Group whereUserId($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Repositories{
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
	class Tag extends \Eloquent {}
}

namespace App\Repositories{
/**
 * Class User
 *
 * @property integer $id
 * @property string  $name
 * @property string  $password
 * @property integer $role
 * @property integer $status
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package App\Repositories
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $favoriteProjects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\DocumentHistory[] $histories
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 * @mixin \Eloquent
 * @property string $email
 * @property string|null $remember_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Repositories{
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
	class PageTag extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\Project
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Attachment[] $attachments
 * @property-read \App\Repositories\Catalog $catalog
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\User[] $favoriteUsers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Document[] $pages
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Project withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name 项目名称
 * @property string|null $description 项目描述
 * @property int $visibility 可见性
 * @property int $user_id 创建用户ID
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property int $sort_level 项目排序，排序值越大越靠后
 * @property int|null $catalog_id 目录ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereSortLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Project whereVisibility($value)
 */
	class Project extends \Eloquent {}
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
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereOperatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\DocumentHistory whereUserId($value)
 */
	class DocumentHistory extends \Eloquent {}
}

namespace App\Repositories{
/**
 * Class Comment
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $user_id
 * @property string  $content
 * @property integer $reply_to_id
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $deleted_at
 * @package App\Repositories
 * @property-read \App\Repositories\Document $document
 * @property-read \App\Repositories\Comment $replyComment
 * @property-read \App\Repositories\User $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Repositories\Comment withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereReplyToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
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
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\Template whereUserId($value)
 */
	class Template extends \Eloquent {}
}

namespace App\Repositories{
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
	class Attachment extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\OperationLogs
 *
 * @property mixed $context
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $user_id 操作用户ID
 * @property string|null $message 日志消息内容
 * @property string $created_at 创建时间
 * @property int|null $project_id 关联的项目ID
 * @property int|null $page_id 关联的文档ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\OperationLogs whereUserId($value)
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
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Repositories\PageShare whereUserId($value)
 */
	class PageShare extends \Eloquent {}
}

