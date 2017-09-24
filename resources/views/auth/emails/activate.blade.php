
@component('mail::message')
# 账号激活

点击以下链接,激活您的账号( {{ $user->email }} ):

    {{ $link }}

@component('mail::button', ['url' => $link])
    激活
@endcomponent

@endcomponent