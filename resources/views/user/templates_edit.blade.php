@extends('layouts.user')

@section('title', '模板管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">个人中心</li>
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:templates') }}">模板管理</a></li>
        <li class="breadcrumb-item active">{{ $template->name }}</li>
    </ol>
@endsection
@section('user-content')
    <div class="card card-white">
        <div class="card-header">编辑模板</div>
        <div class="card-body">

            <form class="form-horizontal" method="post" action="{{ wzRoute('user:templates:edit:handle', ['id' => $template->id])  }}" >
                {{ csrf_field() }}{{ method_field('PUT') }}

                <div class="form-group" style="max-width: 300px;">
                    <label for="editor-name" class="bmd-label-floating">名称</label>
                    <input type="text" class="form-control" value="{{ $template->name }}" id="editor-name" name="name">
                </div>

                <div class="form-group">
                    <label for="editor-description" class="bmd-label-floating">描述</label>
                    <textarea id="editor-description" name="description" class="form-control wz-plain-textarea" rows="3">{{ $template->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="editor-content" class="bmd-label-floating">模板内容</label>
                    <textarea id="editor-content" name="content" class="form-control wz-plain-textarea" rows="20">{{ $template->content }}</textarea>
                </div>

                @can('template-global-create')
                    <div class="form-group">
                        <div class="">
                            <label>
                                <input type="checkbox" name="scope" value="1" {{ $template->scope == 1 ? 'checked': '' }}>
                                @lang('document.template_global_access')
                            </label>
                        </div>
                    </div>
                @else
                    <input type="hidden" name="scope" value="{{ $template->scope }}">
                @endcan

                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised">保存</button>
                    <a class="btn btn-default" href="{{ wzRoute('user:templates') }}">返回</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function () {

        });
    </script>
@endpush