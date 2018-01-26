@extends('layouts.project')
@section('page-content')
    @if($pageID !== 0)
        <div class="wz-panel-breadcrumb">
            <ol class="breadcrumb pull-left">
                <li class="breadcrumb-item"><a href="/home">首页</a></li>
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
                @endcan
                @include('components.page-menus', ['project' => $project, 'pageItem' => $pageItem])
            </ul>
            <div class="clearfix"></div>
        </div>
        <nav class="wz-page-control clearfix">
            <h1 class="wz-page-title">
                {{ $pageItem->title }}
            </h1>
        </nav>

        @include('components.document-info')

        <div class="markdown-body wz-panel-limit {{ $type == 'markdown' ? 'wz-markdown-style-fix' : '' }}" id="markdown-body">
            @if($type == 'markdown')
            <textarea id="append-test" class="d-none">{{ $pageItem->content }}</textarea>
            @endif
        </div>

        <div class="text-center wz-panel-limit mt-3">~ END ~</div>

        @if(count($pageItem->attachments) > 0)
        <div class="wz-attachments wz-panel-limit">
            <hr />
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
                <li class="breadcrumb-item"><a href="/home">首页</a></li>
                <li class="breadcrumb-item active">{{ $project->name }}</li>
            </ol>
        </div>
        <h1>{{ $project->name or '' }}</h1>

        <p class="wz-document-header wz-panel-limit">@lang('document.document_create_info', ['username' => $project->user->name, 'time' => $project->created_at])</p>

        <p class="wz-panel-limit">{{ $project->description or '' }}</p>

        @if(!empty($operationLogs) && $operationLogs->count() > 0)
        <div class="wz-recently-log wz-panel-limit">
            <h4>最近活动</h4>
            @foreach($operationLogs as $log)
                <div class="media text-muted pt-3">
                    <img src="{{ user_face($log->context->username) }}" class="wz-userface-small">
                    <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
                        <strong class="d-block text-gray-dark">{{ $log->created_at }}</strong>
                        @if ($log->message == 'document_updated')
                            <span class="wz-text-dashed">{{ $log->context->username }}</span> 修改了文档
                            <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                            @if(!Auth::guest())
                                【<a href="#" wz-doc-compare-submit
                                    data-doc1="{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $log->context->doc_id]) }}"
                                    data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $log->context->history_id ?? 0, 'id' => $project->id, 'page_id' => $log->context->doc_id]) }}">@lang('common.btn_diff')</a>】
                            @endif
                        @elseif ($log->message == 'document_created')
                            <span class="wz-text-dashed">{{ $log->context->username }}</span> 创建了文档
                            <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                        @elseif ($log->message == 'document_deleted')
                            <span class="wz-text-dashed">{{ $log->context->username }}</span> 删除了文档
                            <span class="wz-text-dashed"><a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $log->context->doc_id]) }}">{{ $log->context->doc_title }}</a></span>
                        @endif
                    </p>
                </div>
            @endforeach
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