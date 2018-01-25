@extends('layouts.project')
@section('page-content')
    <nav class="wz-page-control clearfix">
        <a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $pageItem->id]) }}"
           class="btn btn-link" title="@lang('common.btn_back')"><i class="material-icons">arrow_back</i></a>
        <h1 class="wz-page-title">
            {{ $pageItem->title }}
            <span class="label label-default">文档附件</span>
        </h1>
        <hr />
    </nav>

    <div class="wz-page-content">
        <table class="table">
            <thead>
            <tr>
                <th width="10%">#</th>
                <th>附件名称</th>
                <th width="10%">上传人</th>
                <th width="20%">上传时间</th>
                <th width="15%">@lang('common.operation')</th>
            </tr>
            </thead>
            <tbody>

            @foreach($attachments as $attachment)
                <tr>
                    <th scope="row">{{ $attachment->id }}</th>
                    <td>{{ $attachment->name }}</td>
                    <td>{{ $attachment->user->name }}</td>
                    <td>{{ $attachment->created_at }}</td>
                    <td>
                        <a href="{{ $attachment->path }}">下载</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        @can('page-edit', $pageItem)
                            <a href="#" wz-form-submit data-form="#form-attachment-del-{{ $attachment->id }}"
                               data-confirm="确定要删除该附件？">删除</a>
                            <form id="form-attachment-del-{{ $attachment->id }}"
                                  action="{{ wzRoute('project:doc:attachment:delete', ['id' => $project->id, 'p' => $pageItem->id, 'attachment_id' => $attachment->id]) }}"
                                  method="post">{{ csrf_field() }}{{ method_field('DELETE') }}</form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="wz-pagination">
            {{ $attachments->links() }}
        </div>
    </div>

@endsection

@push('page-panel')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">附件上传</h3>
    </div>
    <div class="panel-body">
        <div class="wz-upload-box">
            @include('components.error', ['error' => $errors ?? null])
            <form class="form-horizontal" method="post"
                  action="{{ wzRoute('project:doc:attachment:upload', ['id' => $project->id, 'p' => $pageItem->id]) }}"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <input type="file" name="attachment">
                    <p class="help-block">支持文件格式：{{ implode('，', $extensions) }}</p>
                </div>

                <div class="form-group">
                    <div class="input-group" style="width: 400px;">
                        <input type="text" class="form-control" name="name"
                               value="{{ old('name') }}" placeholder="附件名称，为空则使用附件文件名">
                        <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary pull-right">确认上传</button>
                    </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush