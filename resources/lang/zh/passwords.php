<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password' => '密码至少应为6个字符并且必须与重复密码相同。',
    'reset'    => '您的密码已经重置!',
    'sent'     => '我们给您发送了一封带有密码重置链接的邮件，请注意查收!',
    'token'    => '密码重置链接已失效。',
    'user'     => "该邮箱地址对应的用户不存在",

    'original_password'       => '原始密码',
    'new_password'            => '新密码',
    'new_password_confirm'    => '重复新密码',
    'change_password_success' => '密码修改成功',

    'validation' => [
        'original_password_required'  => '原始密码不能为空',
        'original_password_unmatch'   => '原始密码不匹配',
        'new_password_required'       => '新密码不能为空',
        'new_password_invalidate'     => '新密码不合法',
        'new_password_at_least'       => '新密码至少为6位字符',
        'new_password_confirm_failed' => '两次输入的密码不匹配',
    ]

];
