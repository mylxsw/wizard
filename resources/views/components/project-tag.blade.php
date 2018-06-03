@if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE && !Auth::guest() && Auth::user()->id == $proj->user_id)
    <span title="@lang('project.privilege_private')" class="wz-box-tag pull-left fa fa-user"></span>
@elseif($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
    <span title="@lang('project.privilege_group_public')" class="wz-box-tag pull-left fa fa-group"></span>
@endif