@extends('layouts.default')

@section('title', '首页')
@section('container-style', 'container container-small')
@section('content')

    <div class="card mt-4 mb-4">
        <div class="card-header">
            <div class="card-header-title">公共主页</div>
            <div class="card-header-operation">
                <div class="bmd-form-group bmd-collapse-inline pull-right">
                    <i class="material-icons search-btn" data-input="#search-input">search</i>
                    <span id="search-input" style="display: none;">
                        <input class="form-control" type="text" id="search" name="search_name" placeholder="搜索项目" value="{{ $name ?? '' }}">
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row marketing wz-main-container-full">
                @unless(Auth::guest())
                    <div class="col alert alert-info alert-dismissible" data-alert-id="public-home-tip">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        提示： 该页面为公共主页，如果要创建项目，请到 <a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>。
                    </div>
                @endunless
                <div class="row col-12">
                    @foreach($projects ?? [] as $proj)
                        <div class="col-3">
                            <a class="wz-box" href="{{ wzRoute('project:home', ['id'=> $proj->id]) }}">
                                @include('components.project-tag', ['proj' => $proj])
                                <p class="wz-title" title="{{ $proj->name }}【排序：{{ $proj->sort_level }}】">{{ $proj->name }}</p>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="wz-pagination">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>

    @if(!Auth::guest())
        <div class="card mb-4">
            <div class="card-header">最近活动</div>
            <div class="card-body" id="operation-log-recently"></div>
        </div>
        @include('components.doc-compare-script')
    @endif

@endsection

@push('script')
    @if(!Auth::guest())
    <script>
        $(function () {
            $.wz.request('get', '{{ wzRoute('operation-log:recently') }}', {}, function (data) {
                $('#operation-log-recently').html(data);
            }, null, 'html');
        });
    </script>
    @endif

    <script>
        $(function () {
            $('.search-btn').on('click', function () {
                $($(this).data('input')).fadeToggle();
            });

            $('#search-input').find('input').keydown(function (event) {
                if (event.keyCode === 13) {
                    window.location = "{{ route('home') }}?name=" + encodeURIComponent($(this).val().trim());
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