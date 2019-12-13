@extends('layouts.project')
@section('page-content')
    <div class="wz-project-main" style="padding-top: 10px;">
        <nav class="wz-page-control clearfix">
            <a href="{{ wzRoute('project:home', ['id' => $project->id, 'p' => $pageItem->id]) }}"
               class="btn btn-link" title="@lang('common.btn_back')"><i class="material-icons">arrow_back</i></a>
            <h1 class="wz-page-title">
                {{ $pageItem->title }}
            </h1>
            <span class="label label-default">文档附件</span>
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
    </div>

@endsection

@push('page-panel')
<div class="card m-3" style="width: 48rem;">
    <div class="card-header">
        附件上传
    </div>
    <div class="card-body">
        <div class="wz-upload-box">
            @include('components.error', ['error' => $errors ?? null])
            <form class="form-horizontal" method="post"
                  action="{{ wzRoute('project:doc:attachment:upload', ['id' => $project->id, 'p' => $pageItem->id]) }}"
                  enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="form-attachment-upload" class="bmd-label-floating">文件上传</label>
                    <input type="file" name="attachment" class="form-control-file" id="form-attachment-upload">
                    <small class="text-muted">支持文件格式：{{ implode('，', $extensions) }}</small>
                </div>

                <div class="form-group" style="width: 40rem;">
                    <label for="form-attachment-name" class="bmd-label-floating">附件名称，为空则使用附件文件名</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="form-attachment-name">

                </div>
                <button type="submit" class="btn btn-primary btn-raised">确认上传</button>
            </form>
        </div>
    </div>
</div>
@endpush