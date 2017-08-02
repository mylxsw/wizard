@extends('layouts.project')
@section('page-content')
    @if($pageID !== 0)
        <nav class="wz-page-control clearfix">
            <h1 class="wz-page-title">{{ $pageItem->title }}</h1>
            <ul class="nav nav-pills pull-right">
                @can('page-edit', $pageItem)
                    <li role="presentation">
                        <a href="{{ wzRoute('project:doc:edit:show', ['id' => $project->id, 'page_id' => $pageItem->id]) }}">编辑</a>
                    </li>
                @endcan
                @include('components.page-menus', ['project' => $project, 'pageItem' => $pageItem])
            </ul>
            <hr />
        </nav>
        <div class="wz-page-content" style="max-width: 400px;">
            <table class="table table-bordered">
                <tr>
                    <th>创建人</th>
                    <td>{{ $pageItem->user->name or '' }}</td>
                </tr>
                <tr>
                    <th>创建时间</th>
                    <td>{{ $pageItem->created_at or '' }}</td>
                </tr>
                <tr>
                    <th>最后修改人</th>
                    <td>{{ $pageItem->lastModifiedUser->name or '' }}</td>
                </tr>
                <tr>
                    <th>最后修改时间</th>
                    <td>{{ $pageItem->updated_at or '' }}</td>
                </tr>

            </table>
        </div>
        <div class="markdown-body" id="markdown-body">
            <textarea id="append-test" style="display:none;">{{ $pageItem->content }}</textarea>
        </div>
    @endif
@endsection

@include('components.markdown-show')