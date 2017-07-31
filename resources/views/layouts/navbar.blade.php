<div class="header clearfix">
    <nav>
        <ul class="nav nav-pills pull-right">
            @if(Auth::guest())
                <li role="presentation"><a href="{{ wzRoute('login') }}">登录</a></li>
            @else
                <li role="presentation">
                    <form action="{{ wzRoute('logout') }}" method="post" id="form-logout">{{ csrf_field() }}</form>
                    <a href="#" class="wz-logout" data-form="#form-logout">退出</a>
                </li>
            @endif
        </ul>
    </nav>
    <h3 class="text-muted">{{ config('app.name', 'Wizard API') }}</h3>
</div>