@extends('layouts.project')
@section('page-content')
    <div class="wz-project-main" style="padding-top: 10px">
        <nav class="wz-page-control clearfix">
            <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}"
               class="btn btn-link"><i class="material-icons">arrow_back</i></a>
            <h1 class="wz-page-title">
                {{ $history->title }} <span class="label label-default">@lang('document.page_history')</span>
            </h1>
            <div class="pull-right" style="position: relative; width: 100px;">
                @can('page-edit', $pageItem)
                    <a href="#" wz-form-submit data-form="#form-recover-{{ $history->id }}"
                       data-confirm="@lang('document.recover_confirm')" style="position: absolute; top: 15px; right: 80px;">
                        <i class="material-icons" title="@lang('document.btn_recover')" data-toggle="tooltip">redo</i>
                        <form id="form-recover-{{ $history->id }}"
                              action="{{ wzRoute('project:doc:history:recover', ['id' => $project->id, 'p' => $pageItem->id, 'history_id' => $history->id]) }}"
                              method="post">{{ csrf_field() }}{{ method_field('PUT') }}</form>
                    </a>
                @endcan
                <a href="#" wz-doc-compare-submit
                   data-doc1="{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id]) }}" style="position: absolute; top: 15px; right: 40px;"
                   data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $history->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    <i class="material-icons" data-toggle="tooltip" title=" @lang('common.btn_diff')">tonality</i>
                </a>
            </div>
            <hr />
        </nav>
        <div class="wz-page-content" style="max-width: 400px;">
            <table class="table table-bordered">
                <tr>
                    <th>@lang('document.creator')</th>
                    <td>{{ $pageItem->user->name }}</td>
                </tr>
                <tr>
                    <th>@lang('document.create_time')</th>
                    <td>{{ $pageItem->created_at }}</td>
                </tr>
                <tr>
                    <th>@lang('document.modified_user')</th>
                    <td>{{ $history->operator->name }}</td>
                </tr>
                <tr>
                    <th>@lang('document.operation_time')</th>
                    <td>{{ $history->created_at }}</td>
                </tr>
            </table>
        </div>
        <div class="markdown-body wz-panel-limit" id="markdown-body">
            @if($type == 'markdown')
                <textarea id="append-test" style="display:none;">{{ $history->content }}</textarea>
            @endif
            @if($type === 'table')
                <textarea id="x-spreadsheet-content" class="d-none">{{ processSpreedSheet($history->content) }}</textarea>
                <div class="wz-spreadsheet">
                    <div id="x-spreadsheet"></div>
                </div>
            @endif
        </div>
    </div>
@endsection

@includeIf("components.{$type}-show", ['isHistoryPage' => true, 'code' => ''])
@include('components.doc-compare-script')