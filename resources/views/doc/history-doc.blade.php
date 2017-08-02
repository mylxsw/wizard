@extends('layouts.project')
@section('page-content')
    <nav class="wz-page-control clearfix">
        <h1 class="wz-page-title">{{ $history->title }} <span class="label label-default">历史文档</span></h1>
        <ul class="nav nav-pills pull-right">
            @can('page-edit', $pageItem)
                <li role="presentation">
                    <a href="#" wz-form-submit data-form="#form-recover-{{ $history->id }}"
                       data-confirm="恢复后将覆盖当前页面，确定要恢复该记录吗？">恢复</a>
                    <form id="form-recover-{{ $history->id }}"
                          action="{{ wzRoute('project:doc:history:recover', ['id' => $project->id, 'p' => $pageItem->id, 'history_id' => $history->id]) }}"
                          method="post">{{ csrf_field() }}{{ method_field('PUT') }}</form>
                </li>
            @endcan
            <li>
                <a href="{{ wzRoute('project:doc:history', ['id' => $project->id, 'page_id' => $pageItem->id ]) }}"
                   class="btn btn-link">返回</a>
            </li>
        </ul>
        <hr />
    </nav>
    <div class="wz-page-content" style="max-width: 400px;">
        <table class="table table-bordered">
            <tr>
                <th>创建人</th>
                <td>{{ $pageItem->user->name }}</td>
            </tr>
            <tr>
                <th>创建时间</th>
                <td>{{ $pageItem->created_at }}</td>
            </tr>
            <tr>
                <th>变更人</th>
                <td>{{ $history->operator->name }}</td>
            </tr>
            <tr>
                <th>变更时间</th>
                <td>{{ $history->created_at }}</td>
            </tr>
        </table>
    </div>
    <div class="markdown-body" id="markdown-body">
        <textarea id="append-test" style="display:none;">{{ $history->content }}</textarea>
    </div>
@endsection

@include('components.markdown-show')