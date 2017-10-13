<nav class="navbar navbar-default wz-top-navbar">
    <div class="@yield('container-style')">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">{{ config('app.name', 'Wizard API') }}</a>
        </div>
        <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::guest())
                    <li role="presentation"><a href="{{ wzRoute('login') }}">@lang('common.login')</a></li>
                    <li role="presentation"><a href="{{ wzRoute('register') }}">@lang('common.register')</a></li>
                @else
                    <li role="presentation"><a href="/">主页</a></li>
                    <li role="presentation"><a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a></li>
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name ?? Auth::user()->email }}
                            @if(userHasNotifications())
                                <sup class="text-danger wz-message-tip" title="您有未读消息">{{ userNotificationCount() }}</sup>
                            @endif
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li role="presentation">
                                <a href="{{ wzRoute('user:notifications') }}">
                                    <span class="glyphicon glyphicon-bell"></span>
                                    通知
                                    @if(userHasNotifications())
                                        <span class="badge">{{ userNotificationCount() }}</span>
                                    @endif
                                </a>
                                <a href="{{ wzRoute('user:basic') }}">
                                    <span class="glyphicon glyphicon-user"></span>
                                    @lang('common.user_info')
                                </a>
                                <a href="{{ wzRoute('user:password') }}">
                                    <span class="glyphicon glyphicon-usd"></span>
                                    @lang('common.change_password')
                                </a>
                                <a href="{{ wzRoute('user:templates') }}">
                                    <span class="glyphicon glyphicon-duplicate"></span>
                                    @lang('common.template_maintenance')
                                </a>
                                @if(Auth::user()->isAdmin())
                                    <a href="{!! wzRoute('admin:groups') !!}">
                                        <span class="glyphicon glyphicon-cog"></span>
                                        系统管理
                                    </a>
                                @endif
                            </li>
                            <li role="presentation">
                                <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                                <a href="#" wz-form-submit data-confirm="@lang('common.logout_confirm')" data-form="#form-logout">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    @lang('common.logout')
                                </a>
                            </li>
                        </ul>
                    </li>

                @endif
            </ul>
        </div>
    </div>
</nav>