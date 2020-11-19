<div class="wz-top-navbar d-flex flex-column flex-md-row align-items-center px-md-4 bg-white border-bottom box-shadow @impersonating() wz-impersonating @endImpersonating">
    <h5 class="my-0 mr-md-auto font-weight-normal wz-top-nav-item">
        <a href="/">{{ config('app.name', 'Wizard API') }}</a>
    </h5>
    <div class="wz-top-nav-search ml-md-auto d-flex justify-content-end">
        <form action="{{ wzRoute('search:search') }}" method="get">
            <label for="search-keyword"></label>
            <input type="text" placeholder="@lang('common.search')" id="search-keyword" name="keyword" value="{{ $keyword ?? '' }}">
        </form>
    </div>
    <button type="button" class="btn bmd-btn-icon wz-theme-indicator" data-toggle="tooltip" title="切换主题">
        <i class="material-icons">wb_sunny</i>
    </button>
    @if (Auth::guest())
        <a class="btn btn-info active" href="{{ wzRoute('login') }}">@lang('common.login')</a>
        {{--<a class="btn btn-outline-primary" href="{{ wzRoute('register') }}">@lang('common.register')</a>--}}
    @else
        <nav class="my-2 my-md-0 wz-top-nav-item">
            <a class="p-2 text-dark dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @impersonating() 扮演：@endImpersonating
                {{ Auth::user()->name ?? Auth::user()->email }}
                @if(userHasNotifications())
                    <sup class="wz-message-tip" title="您有未读消息">{{ userNotificationCount(99) }}</sup>
                @endif
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{ wzRoute('user:home') }}">
                    <i class="fa fa-home mr-2"></i> @lang('common.user_home')
                </a>
                <a href="{{ wzRoute('user:notifications') }}" class="dropdown-item">
                    <i class="fa fa-bell mr-2"></i> 通知
                    @if(userHasNotifications())
                        <span class="badge">{{ userNotificationCount(999) }}</span>
                    @endif
                </a>
                <a href="{{ wzRoute('user:basic') }}" class="dropdown-item">
                    <i class="fa fa-user mr-2"></i> @lang('common.user_info')
                </a>
                @if (!ldap_enabled())
                <a href="{{ wzRoute('user:password') }}" class="dropdown-item">
                    <i class="fa fa-lock mr-2"></i> @lang('common.change_password')
                </a>
                @endif
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
        @impersonating()
        <button type="button" class="btn bmd-btn-icon" data-toggle="tooltip" title="停止扮演" wz-form-submit data-form="#stop-impersonate">
            <i class="fa fa-user-secret wz-theme-support-icon"></i>
            <form action="{{ wzRoute('impersonate:stop') }}" method="post" id="stop-impersonate">{{method_field('DELETE')}}{{ csrf_field() }}</form>
        </button>
        @endImpersonating
    @endif
</div>

@push('script')
    <script>
        $(function () {
            $('#search-keyword').keydown(function (event) {
                if (event.keyCode === 13) {
                    $(this).parents('form').submit();
                }
            });

            // 主题自动切换
            (function () {
                var currentTheme = store.get('wizard-theme');
                if (currentTheme === undefined) {
                    currentTheme = '{{ config('wizard.theme') }}';
                }

                var themeIndicator = $('.wz-theme-indicator .material-icons');
                themeIndicator.text(currentTheme === 'dark' ? 'brightness_3' : 'wb_sunny');

                $('.wz-theme-indicator').on('click', function () {
                    if (currentTheme === 'default') {
                        currentTheme = 'dark';
                        themeIndicator.text('brightness_3');
                        $('body').addClass('wz-dark-theme');
                    } else {
                        currentTheme = 'default';
                        themeIndicator.text('wb_sunny');
                        $('body').removeClass('wz-dark-theme');
                    }

                    store.set('wizard-theme', currentTheme);
                });
            })();
        });
    </script>
@endpush