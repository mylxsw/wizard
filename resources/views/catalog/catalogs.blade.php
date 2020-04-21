@extends('layouts.admin')

@section('title', '项目目录管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">系统管理</li>
        <li class="breadcrumb-item active">项目目录管理</li>
    </ol>
@endsection
@section('admin-content')
    <div class="card card-white">
        <div class="card-header">创建项目目录</div>
        <div class="card-body">
            <form method="post"
                  action="{!! wzRoute('admin:catalogs:add') !!}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="form-name" class="bmd-label-floating">目录名称</label>
                    <input id="form-name" type="text" name="name" class="form-control" value="{{ old('name') }}" />
                </div>
                <div class="form-group">
                    <label for="catalog-sort" class="bmd-label-floating">排序（值越大越靠后）</label>
                    <input type="number" name="sort_level" class="form-control float-left w-75" id="catalog-sort" value="1000" />
                </div>
                <div class="form-group mt-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_in_home" value="1" checked> 在首页展示
                        </label>
                    </div>
                </div>
                <br/>
                <div class="form-group">
                    <button type="submit" class="btn btn-raised btn-primary" >创建目录</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3 card-white">
        <div class="card-header">项目目录</div>
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>目录名称</th>
                <th>项目数目</th>
                <th>排序</th>
                <th>首页展示</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">-</th>
                <td><a href="{{ wzRoute('home') }}">无</a></td>
                <td>{{ $catalogs_none }}</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @foreach($catalogs as $cat)
                <tr>
                    <th scope="row">{{ $cat->id }}</th>
                    <td><a href="{{ wzRoute('home', ['catalog' => $cat->id]) }}">{{ $cat->name }}</a></td>
                    <td>{{ $cat->projects_count }}</td>
                    <td>{{ $cat->sort_level }}</td>
                    <td><i class="material-icons text-{{ ($cat->show_in_home ?? true) ? 'success':'warning' }}">{{ ($cat->show_in_home ?? true) ? 'check':'close' }}</i></td>
                    <td>
                        <a href="{!! wzRoute('admin:catalogs:view', ['id' => $cat->id]) !!}">
                            <i class="material-icons" title="编辑">create</i>
                        </a>
                        &nbsp;
                        <a href="#" wz-form-submit data-form="#form-cat-{{ $cat->id }}"
                           data-confirm="确定要删除该目录？删除后所有内部项目将重置为无目录">
                            <i class="material-icons text-danger" title="@lang('common.btn_delete')">delete_sweep</i>
                            <form id="form-cat-{{ $cat->id }}" method="post"
                                  action="{{ wzRoute('admin:catalogs:delete', ['id' => $cat->id]) }}">
                                {{ method_field('DELETE') }}{{ csrf_field() }}
                            </form>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
