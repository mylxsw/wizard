@extends('layouts.project')
@section('page-content')
    @if($pageID !== 0)
        <div class="wz-panel-breadcrumb">
            <ol class="breadcrumb pull-left">
                <li class="breadcrumb-item"><a href="{{ wzRoute('home') }}">首页</a></li>
                @if(!empty($project->catalog))
                    <li class="breadcrumb-item"><a href="{{ wzRoute('home', ['catalog' => $project->catalog->id]) }}">{{ $project->catalog->name }}</a></li>
                @endif
                <li class="breadcrumb-item"><a href="{{ wzRoute('project:home', ['id' => $project->id]) }}">{{ $project->name }}</a></li>
                <li class="breadcrumb-item active">{{ $pageItem->title }}</li>
            </ol>
            <ul class="nav nav-pills pull-right">
                @can('page-edit', $pageItem)
                    <li role="presentation" class="mr-2">
                        <button type="button" data-href="{{ wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}" title="@lang('common.btn_edit')" class="btn btn-primary bmd-btn-icon">
                            <i class="material-icons">mode_edit</i>
                        </button>
                    </li>
                    @if(!empty($history))
                        <li role="presentation" class="mr-2">
                            <button type="button" wz-doc-compare-submit
                               data-doc1="{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                               data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $history->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}"
                               title="@lang('common.btn_diff')" class="btn btn-primary  bmd-btn-icon">
                                <i class="material-icons">history</i>
                            </button>
                        </li>
                    @endif
                    <li role="presentation" class="mr-2">
                        <button type="button" data-href="{{ wzRoute('project:doc:attachment', ['id' => $project->id, 'page_id' => $pageItem->id]) }}" title="附件" class="btn btn-primary bmd-btn-icon">
                            <span class="icon-paper-clip"></span>
                        </button>
                    </li>
                @endcan
                @include('components.page-menus', ['project' => $project, 'pageItem' => $pageItem])
            </ul>
            <div class="clearfix"></div>
        </div>
        <nav class="wz-page-control clearfix">
            <h1 class="wz-page-title">
                {{ $pageItem->title }}
                @if($type == 'swagger')
                    <a title="原始Swagger文档" target="_blank" href="{{ route('swagger:doc:yml', ['id' => $project->id, 'page_id' => $pageItem->id]) }}" class="icon-link"></a>
                @endif
            </h1>
        </nav>
        @include('components.document-info')
        @include('components.tags')

        <div class="markdown-body wz-panel-limit {{ $type == 'markdown' ? 'wz-markdown-style-fix' : '' }}" id="markdown-body">
            @if($type == 'markdown')
            <textarea id="append-test" class="d-none">{{ $pageItem->content }}</textarea>
            @endif
        </div>

        <div class="text-center wz-panel-limit mt-3">~ END ~</div>

        @if(count($pageItem->attachments) > 0)
        <div class="wz-attachments wz-panel-limit">
            <h4>附件</h4>
            <ol>
                @foreach($pageItem->attachments as $attachment)
                    <li>
                        <a href="{{ $attachment->path }}">
                            <span class="glyphicon glyphicon-download-alt"></span>
                            {{ $attachment->name }}
                            <span class="wz-attachment-info">
                                【{{ $attachment->user->name }}，
                                {{ $attachment->created_at }}】
                            </span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
        @endif

    @else
        <div class="wz-panel-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ wzRoute('home') }}">首页</a></li>
                @if(!empty($project->catalog))
                    <li class="breadcrumb-item"><a href="{{ wzRoute('home', ['catalog' => $project->catalog->id]) }}">{{ $project->catalog->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $project->name }}</li>
            </ol>
        </div>
        <h1>{{ $project->name or '' }}</h1>

        <p class="wz-document-header wz-panel-limit">@lang('document.document_create_info', ['username' => $project->user->name, 'time' => $project->created_at])</p>
        <p class="wz-panel-limit">{{ $project->description or '' }}</p>

        @if (!Auth::guest())
            <div class="wz-recently-log wz-panel-limit">
                <h4>最近活动</h4>
                <div id="operation-log-recently"></div>
            </div>
        @endif

        @if($project->groups->count() > 0)
            <div class="wz-group-allowed-list wz-panel-limit">
                <h4>@lang('project.group_added')</h4>
                <table class="table">
                    <caption></caption>
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
            </div>
        @endif
    @endif
@endsection

@includeIf("components.{$type}-show")

@push('page-panel')

    @if($pageID != 0 && !(Auth::guest() && count($pageItem->comments) === 0))
        @include('components.comment')
    @endif

    @if(!Auth::guest())
        @include('components.doc-compare-script')
    @endif
@endpush

@push('script')
    @if(!Auth::guest())
        <script src="/assets/vendor/moment-with-locales.min.js"></script>
        <script>
            $(function () {
                moment.locale('zh-cn');
                $.wz.request('get', '{!! wzRoute('operation-log:recently', ['limit' => 'project', 'project_id' => $project->id]) !!}', {}, function (data) {
                    if (data.trim() === '') {
                        $('.wz-recently-log').addClass('d-none');
                    } else {
                        $('#operation-log-recently').html(data);
                        $('#operation-log-recently .wz-operation-log-time').map(function() {
                            $(this).html(moment($(this).html(), 'YYYY-MM-DD hh:mm:ss').fromNow());
                        });
                    }
                }, null, 'html');
            });
        </script>
    @endif
@endpush
