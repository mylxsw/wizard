@extends('layouts.project')
@section('page-content')
    @if($pageID !== 0)
        <nav class="wz-page-control clearfix">
            <h1 class="wz-page-title">{{ $pageItem->title }}</h1>
            <ul class="nav nav-pills pull-right">
                @can('page-edit', $pageItem)
                    <li role="presentation">
                        <a href="{{ wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">@lang('common.btn_edit')</a>
                    </li>
                @endcan
                @include('components.page-menus', ['project' => $project, 'pageItem' => $pageItem])
            </ul>
            <hr />
        </nav>
        <div class="wz-page-content" style="max-width: 400px;">
            <table class="table table-bordered">
                <tr>
                    <th>@lang('document.creator')</th>
                    <td>{{ $pageItem->user->name or '' }}</td>
                </tr>
                <tr>
                    <th>@lang('document.create_time')</th>
                    <td>{{ $pageItem->created_at or '' }}</td>
                </tr>
                <tr>
                    <th>@lang('document.last_modified_user')</th>
                    <td>{{ $pageItem->lastModifiedUser->name or '' }}</td>
                </tr>
                <tr>
                    <th>@lang('document.last_modified_time')</th>
                    <td>{{ $pageItem->updated_at or '' }}</td>
                </tr>

            </table>
        </div>
        <div class="markdown-body" id="markdown-body">
            <textarea id="append-test" style="display:none;">{{ $pageItem->content }}</textarea>
        </div>
    @else
        <h1>{{ $project->name or '' }}</h1>
        <hr/>
        <p>{{ $project->description or '' }}</p>

        <p>该项目由 {{ $project->user->name or '' }} 创建于 {{ $project->created_at }}。</p>
        @if($project->groups->count() > 0)
            <table class="table">
                <caption>@lang('project.group_added')</caption>
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('project.group_name')</th>
                    <th>@lang('project.group_write_enabled')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($project->groups as $group)
                    <tr>
                        <th scope="row">{{ $group->id }}</th>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->projects[0]->pivot->privilege == 1 ? __('common.yes') : __('common.no') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endif
@endsection

@include('components.markdown-show')