@extends('layouts.admin')

@section('title', '项目目录管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item"><a href="{{ wzRoute('admin:catalogs') }}">项目目录管理</a></li>
        <li class="breadcrumb-item active">{{ $catalog->name }}</li>
    </ol>
@endsection
@section('admin-content')
    <div class="card card-white">
        <div class="card-header">编辑目录信息</div>
        <div class="card-body">

            <form class="form-horizontal" method="post" action="{{ wzRoute('admin:catalogs:edit', ['id' => $catalog->id])  }}" style="max-width: 300px;">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="editor-name" class="bmd-label-floating">目录名称</label>
                    <input type="text" class="form-control" value="{{ $catalog->name }}" id="editor-name" name="name">
                </div>
                <div class="form-group">
                    <label for="catalog-sort" class="bmd-label-floating">排序（值越大越靠后）</label>
                    <input type="number" name="sort_level" class="form-control float-left w-75" id="catalog-sort" value="{{ $catalog->sort_level }}" />
                </div>

                <div class="form-group mt-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_in_home" value="1" {{ ($catalog->show_in_home ?? true) ? 'checked':'' }}> 在首页展示
                        </label>
                    </div>
                </div>

                <br/>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-raised" >保存</button>
                    <a class="btn btn-default" href="{{ wzRoute('admin:catalogs') }}">返回</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 card-white">
        <div class="card-header">包含的项目</div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>项目名称</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @forelse($catalog->projects as $pro)
                <tr>
                    <td>{{ $pro->id }}</td>
                    <td>{{ $pro->name }}</td>
                    <td>
                        <form id="form-user-{{ $pro->id }}" method="post"
                              action="{!! wzRoute('admin:catalogs:project:del', ['id' => $catalog->id, 'project_id' => $pro->id]) !!}">
                            {{ method_field('DELETE') }}{{ csrf_field() }}
                        </form>
                        <a href="#" wz-form-submit data-form="#form-user-{{ $pro->id }}"
                           data-confirm="确定要将项目从该目录移除？">
                            <i class="material-icons text-danger" title="解除">remove_circle_outline</i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">没有符合条件的信息！</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function () {
            // 鼠标经过提示
            $('[data-toggle="tooltip"]').tooltip({
                delay: { "show": 500, "hide": 100 }
            });
        });
    </script>
@endpush