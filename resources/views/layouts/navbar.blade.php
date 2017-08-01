<div class="header clearfix">
    <nav>
        <ul class="nav nav-pills pull-right">
            @if(Auth::guest())
                <li role="presentation"><a href="{{ wzRoute('login') }}">登录</a></li>
                <li role="presentation"><a href="{{ wzRoute('register') }}">注册</a></li>
            @else
                <li role="presentation"><a href="{{ route('user:home') }}">个人主页</a></li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name ?? Auth::user()->email }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation">
                            <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                            <a href="#" wz-form-submit data-confirm="确定要退出吗？" data-form="#form-logout">退出</a>
                        </li>
                    </ul>
                </li>

            @endif
        </ul>
    </nav>
    <h3 class="text-muted"><a href="/">{{ config('app.name', 'Wizard API') }}</a></h3>
</div>