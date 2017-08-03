/**
 * Created by mylxsw on 2017/8/3.
 */

// 全局变量、函数
$.global = {};

$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 超链接触发表单提交事件
    $('a[wz-form-submit]').on('click', function (e) {
        e.preventDefault();

        var form = $($(this).data('form'));
        var confirm = $(this).data('confirm');

        if (confirm === undefined) {
            form.submit();
        }

        $.wz.confirm(confirm, function () {
            form.submit();
        });
    });
});