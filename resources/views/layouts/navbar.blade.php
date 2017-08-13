<div class="header clearfix">
    <nav>
        <ul class="nav nav-pills pull-right">
            @if(Auth::guest())
                <li role="presentation"><a href="{{ wzRoute('login') }}">@lang('common.login')</a></li>
                <li role="presentation"><a href="{{ wzRoute('register') }}">@lang('common.register')</a></li>
            @else
                <li role="presentation"><a href="{{ wzRoute('user:home') }}">@lang('common.user_home')</a></li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name ?? Auth::user()->email }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation">
                            <a href="{{ wzRoute('user:basic') }}">@lang('common.user_info')</a>
                            <a href="{{ wzRoute('user:password') }}">@lang('common.change_password')</a>
                            <a href="#" wz-wait-develop>@lang('common.template_maintenance')</a>
                        </li>
                        <li role="presentation">
                            <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                            <a href="#" wz-form-submit data-confirm="@lang('common.logout_confirm')" data-form="#form-logout">@lang('common.logout')</a>
                        </li>
                    </ul>
                </li>

            @endif
        </ul>
    </nav>
    <h3 class="text-muted"><a href="/">{{ config('app.name', 'Wizard API') }}</a></h3>
</div>