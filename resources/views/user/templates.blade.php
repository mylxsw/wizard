@extends('layouts.user')

@section('title', '模板管理')
@section('breadcrumb')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ wzRoute('user:home') }}">@lang('common.home')</a></li>
        <li class="breadcrumb-item">个人中心</li>
        <li class="breadcrumb-item active">模板管理</li>
    </ol>
@endsection
@section('user-content')
    <div class="card card-white">
        <div class="card-body">
            <table class="table table-hover wz-message-table">
                <thead>
                <tr>
                    <th style="width: 56px;">类型</th>
                    <th style="min-width: 200px;">名称</th>
                    <th>描述</th>
                    <th style="width: 85px;">操作</th>
                </tr>
                </thead>
                <tbody>
                @forelse($templates as $temp)
                    <tr>
                        <td>
                            <p><img src="/assets/{{ $temp->type == 1 ? 'swagger' : 'markdown' }}.png" style="width: 20px;" title="{{ $temp->type == 1 ? 'swagger' : 'markdown' }}"/></p>
                            @if($temp->scope == \App\Repositories\Template::SCOPE_GLOBAL)
                                <p><i class="material-icons" title="与他人共享的">people</i></p>
                            @endif
                        </td>
                        <td>
                            <p><a href="{{ wzRoute('user:templates:edit', ['id' => $temp->id]) }}">{{ $temp->name }}</a></p>
                            <p style="color: #808080;">{{ $temp->updated_at->format('Y-m-d H:i') }}</p>
                        </td>
                        <td>{{ $temp->description }}</td>
                        <td>
                            <a href="{{ wzRoute('user:templates:edit', ['id' => $temp->id]) }}"><i class="material-icons" title="编辑">create</i> </a>
                            &nbsp;
                            <a href="#" wz-form-submit data-form="#form-template-{{ $temp->id }}"
                               data-confirm="确定要删除该模板？">
                                <i class="material-icons text-danger" title="@lang('common.btn_delete')">delete_sweep</i>
                                <form id="form-template-{{ $temp->id }}" method="post"
                                      action="{{ wzRoute('user:templates:delete', ['id' => $temp->id]) }}">
                                    {{ method_field('DELETE') }}{{ csrf_field() }}
                                </form>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="4">没有符合条件的信息！</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
