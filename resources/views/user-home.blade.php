@extends('layouts.default')

@section('title', '个人首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4">
        <div class="card-header">
            <div class="card-header-title">我的项目</div>
            <div class="card-header-operation">
                <div class="bmd-form-group bmd-collapse-inline pull-right">
                    <i class="material-icons search-btn" data-input="#search-input">search</i>
                    <span id="search-input" style="{{ empty($name) ? 'display: none;' : '' }}">
                        <input class="form-control" type="text" id="search" name="search_name" placeholder="搜索文档" value="{{ $name ?? '' }}">
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row marketing wz-main-container-full col-12">
                @foreach($projects ?? [] as $proj)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a class="wz-box wz-box-project" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}" >
                            @if($proj->visibility == \App\Repositories\Project::VISIBILITY_PRIVATE)
                                <span title="@lang('project.privilege_private')" class="wz-box-tag fa fa-eye-slash"></span>
                            @endif
                            <p class="wz-title" title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                            <p class="wz-page-count">{{ $proj->pages_count ?? '0' }} 个文档</p>
                            @if(!empty($proj->catalog_id))
                                <span title="所属目录" class="wz-box-tag pull-right wz-project-count">{{ $proj->catalog->name ?? '' }}</span>
                            @endif
                        </a>
                    </div>
                @endforeach
                @can('project-create')
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a class="wz-box wz-box-new" href="#"
                           data-toggle="modal" data-target="#wz-new-project">
                            <p class="wz-title"><span class="fa fa-plus"></span> @lang('project.new_project')</p>
                        </a>
                    </div>
                @else
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <span class="wz-box-disabled">
                    <p class="wz-title" title="您没有创建项目的权限">
                        <span class="fa fa-ban"></span>
                        @lang('project.new_project')
                    </p>
                </span>
                    </div>
                @endcan
            </div>
            <div class="row">
                <div class="wz-pagination">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 mb-4">
        <div class="card-header">最近活动</div>
        <div class="card-body" id="operation-log-recently"></div>
        <div class="card-footer text-center">
            <a href="#" class="wz-load-more">加载更多</a>
        </div>
        @include('components.doc-compare-script')
    </div>

    @can('project-create')
        <div class="modal fade" id="wz-new-project" tabindex="-1" role="dialog" aria-labelledby="wz-new-project">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('project.new_project')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ wzRoute('project:new:handle') }}" id="wz-project-save-form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="project-name" class="bmd-label-floating">@lang('project.project_name')：</label>
                                <input type="text" name="name" placeholder="@lang('project.project_name')" class="form-control" id="project-name">
                            </div>
                            <div class="form-group">
                                <label for="catalog-status" class="bmd-label-floating">目录</label>
                                <select id="catalog-status" name="catalog" class="form-control">
                                    <option value="0" selected>无</option>
                                    @foreach($catalogs as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="project-description" class="bmd-label-floating">@lang('project.description')：</label>
                                <textarea class="form-control" name="description" placeholder="@lang('project.description')" id="project-description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="project-visibility" class="bmd-label-floating">@lang('project.privilege')：</label>
                                <div class="radio mt-2">
                                    <label class="radio-inline">
                                        <input type="radio" name="visibility" id="project-visibility" value="{{ \App\Repositories\Project::VISIBILITY_PUBLIC }}" checked>
                                        @lang('project.privilege_public')
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="visibility" value="{{ \App\Repositories\Project::VISIBILITY_PRIVATE }}">
                                        @lang('project.privilege_private')
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="project-sort" class="bmd-label-floating">排序（值越大越靠后）</label>
                                <input type="number" name="sort_level" class="form-control float-left w-75" id="project-sort" value="1000" {{ Auth::user()->can('project-sort') ? '' : 'disabled' }}/>
                                <i class="fa fa-question-circle ml-2" data-toggle="tooltip" title="" data-original-title="只有管理员可以修改"></i>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-raised mr-2" id="wz-project-save">@lang('common.btn_save')</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('common.btn_close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('script')
<script src="/assets/vendor/moment-with-locales.min.js"></script>
<script>
    $(function() {
        $('#wz-project-save').on('click', function () {
            var form = $('#wz-project-save-form');

            $.wz.btnAutoLock($(this));

            $.wz.asyncForm(form, {}, function (data) {
                window.location.reload(true);
            });
        });

        // 最近活动加载
        moment.locale('zh-cn');

        var getRecentlyLogs = function (offset) {
            $('.wz-load-more').html('加载中...');
            $.wz.request('get', '{{ wzRoute('operation-log:recently', ['limit' => 'my']) }}', {offset: offset}, function (data) {
                $('#operation-log-recently').append(data);

                $('#operation-log-recently .wz-operation-log-time').map(function() {
                    $(this).html(moment($(this).prop('title'), 'YYYY-MM-DD hh:mm:ss').fromNow());
                });

                if (data.trim() === "") {
                    $('.wz-load-more').parent().html('没有更多了...');
                } else {
                    $('.wz-load-more').html('加载更多');
                }

            }, null, 'html');
        };

        // 初次加载最近操作日志
        getRecentlyLogs(0);

        $('.wz-load-more').click(function(e) {
            e.preventDefault();
            var offset = $('#operation-log-recently .wz-operation-log-time').size();
            if (offset > 100) {
                $(this).parent().html('只能加载这么多了...');
                return;
            }

            getRecentlyLogs(offset);
        });


        $('.search-btn').on('click', function () {
            var inputItem = $($(this).data('input'));
            inputItem.fadeToggle();
            inputItem.find('input').focus();
        });

        $('#search-input').find('input').keydown(function (event) {
            if (event.keyCode === 13) {
                {{--window.location = "{{ route('user:home') }}?name=" + encodeURIComponent($(this).val().trim());--}}
                window.location = "{{ wzRoute('search:search') }}?range=my&keyword=" + encodeURIComponent($(this).val().trim());
            }
        }).blur(function () {
            var value = $(this).val().trim();
            if (value === '') {
                $(this).parent('#search-input').fadeToggle();
            }
        });
    });
</script>
@endpush
