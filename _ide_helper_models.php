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
 * @property integer $pid
 * @property string  $title
 * @property string  $description
 * @property string  $content
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $type
 * @property integer $status
 * @package App\Repositories
 * @property-read \App\Repositories\Project $project
 * @property-read \App\Repositories\User $user
 */
	class Page extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\Project
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Page[] $pages
 * @property-read \App\Repositories\User $user
 */
	class Project extends \Eloquent {}
}

namespace App\Repositories{
/**
 * App\Repositories\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Page[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Repositories\Project[] $projects
 */
	class User extends \Eloquent {}
}

