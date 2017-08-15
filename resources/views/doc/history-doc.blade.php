@extends('layouts.project')
@section('page-content')
    <nav class="wz-page-control clearfix">
        <h1 class="wz-page-title">{{ $history->title }} <span class="label label-default">@lang('document.page_history')</span></h1>
        <ul class="nav nav-pills pull-right">
            @can('page-edit', $pageItem)
                <li role="presentation">
                    <a href="#" wz-form-submit data-form="#form-recover-{{ $history->id }}"
                       data-confirm="@lang('document.recover_confirm')">@lang('document.btn_recover')</a>
                    <form id="form-recover-{{ $history->id }}"
                          action="{{ wzRoute('project:doc:history:recover', ['id' => $project->id, 'p' => $pageItem->id, 'history_id' => $history->id]) }}"
                          method="post">{{ csrf_field() }}{{ method_field('PUT') }}</form>
                </li>
            @endcan
            <li>
                <a href="#" wz-doc-compare-submit
                   data-doc1="{{ wzRoute('project:doc:json', ['id' => $project->id, 'page_id' => $pageItem->id]) }}"
                   data-doc2="{{ wzRoute('project:doc:history:json', ['history_id' => $history->id, 'id' => $project->id, 'page_id' => $pageItem->id]) }}">
                    @lang('common.btn_diff')
                </a>
            </li>
            <li>
                <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}"
                   class="btn btn-link">@lang('common.btn_back')</a>
            </li>
        </ul>
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
    <div class="markdown-body" id="markdown-body">
        @if($type == 'markdown')
        <textarea id="append-test" style="display:none;">{{ $history->content }}</textarea>
        @endif
    </div>
@endsection

@includeIf("components.{$type}-show", ['isHistoryPage' => true, 'code' => ''])
@include('components.doc-compare-script')