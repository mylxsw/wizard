<div class="wz-top-navbar d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-white border-bottom box-shadow ">
    <h5 class="my-0 mr-md-auto font-weight-normal">
        <a href="/">{{ config('app.name', 'Wizard API') }}</a>
    </h5>

    @if (Auth::guest())
        <a class="btn btn-outline-primary" href="{{ wzRoute('login') }}">@lang('common.login')</a>
        {{--<a class="btn btn-outline-primary" href="{{ wzRoute('register') }}">@lang('common.register')</a>--}}
    @else
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="/">主页</a>
            <a class="p-2 text-dark" href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a>
            <a class="p-2 text-dark dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name ?? Auth::user()->email }}
                @if(userHasNotifications())
                    <sup class="text-danger wz-message-tip" title="您有未读消息">{{ userNotificationCount() }}</sup>
                @endif
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a href="{{ wzRoute('user:notifications') }}" class="dropdown-item">
                    通知
                    @if(userHasNotifications())
                        <span class="badge">{{ userNotificationCount() }}</span>
                    @endif
                </a>
                <a href="{{ wzRoute('user:basic') }}" class="dropdown-item">
                    @lang('common.user_info')
                </a>
                <a href="{{ wzRoute('user:password') }}" class="dropdown-item">
                    @lang('common.change_password')
                </a>
                <a href="{{ wzRoute('user:templates') }}" class="dropdown-item">
                    @lang('common.template_maintenance')
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{!! wzRoute('admin:groups') !!}" class="dropdown-item">
                        系统管理
                    </a>
                @endif
                <a href="#" wz-form-submit data-confirm="@lang('common.logout_confirm')" data-form="#form-logout" class="dropdown-item">
                    @lang('common.logout')
                    <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                </a>
            </div>
        </nav>
    @endif
</div>