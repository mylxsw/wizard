@if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE && !Auth::guest() && Auth::user()->id == $proj->user_id)
    <span title="@lang('project.privilege_private')" class="wz-box-tag pull-right icon-user"></span>
@elseif($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
    <span title="@lang('project.privilege_group_public')" class="wz-box-tag pull-right icon-group"></span>
@endif