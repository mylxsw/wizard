<div class="wz-top-navbar d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-white border-bottom box-shadow ">
    <h5 class="my-0 mr-md-auto font-weight-normal">
        <a href="/">{{ config('app.name', 'Wizard API') }}</a>
    </h5>

    @if (Auth::guest())
        <a class="btn btn-outline-primary" href="{{ wzRoute('login') }}">@lang('common.login')</a>
        {{--<a class="btn btn-outline-primary" href="{{ wzRoute('register') }}">@lang('common.register')</a>--}}
    @else
        <nav class="my-2 my-md-0">
            <a class="p-2 text-dark" href="/">主页</a>
            <a class="p-2 text-dark" href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>
            <a class="p-2 text-dark" href="{{ wzRoute('search:search') }}">搜索</a>
            <a class="p-2 text-dark dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name ?? Auth::user()->email }}
                @if(userHasNotifications())
                    <sup class="wz-message-tip" title="您有未读消息">{{ userNotificationCount(99) }}</sup>
                @endif
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a href="{{ wzRoute('user:notifications') }}" class="dropdown-item">
                    <i class="fa fa-bell mr-2"></i> 通知
                    @if(userHasNotifications())
                        <span class="badge">{{ userNotificationCount() }}</span>
                    @endif
                </a>
                <a href="{{ wzRoute('user:basic') }}" class="dropdown-item">
                    <i class="fa fa-user mr-2"></i> @lang('common.user_info')
                </a>
                <a href="{{ wzRoute('user:password') }}" class="dropdown-item">
                    <i class="fa fa-lock mr-2"></i> @lang('common.change_password')
                </a>
                <a href="{{ wzRoute('user:templates') }}" class="dropdown-item">
                    <i class="fa fa-th mr-2"></i> @lang('common.template_maintenance')
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{!! wzRoute('admin:dashboard') !!}" class="dropdown-item">
                        <i class="fa fa-cog mr-2"></i> 系统管理
                    </a>
                @endif
                <a href="#" wz-form-submit data-confirm="@lang('common.logout_confirm')" data-form="#form-logout" class="dropdown-item">
                    <i class="fa fa-power-off mr-2"></i> @lang('common.logout')
                    <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                </a>
            </div>
        </nav>
    @endif
</div>
