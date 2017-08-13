/**
 * Created by mylxsw on 2017/8/3.
 */
$.wz = {
    /**
     * 发起异步请求
     *
     * @param method
     * @param url
     * @param params
     * @param successCallback
     * @param errorCallback 返回true则跳过默认逻辑，返回false则继续执行默认逻辑
     */
    request: function (method, url, params, successCallback, errorCallback) {
        successCallback = successCallback || function (data) {};
        errorCallback = errorCallback || function (response) { return false; };

        $.ajax({
            url: url,
            type: method,
            data: params,
            dataType: 'json',
            success: successCallback,
            error: function (response) {
                if (errorCallback(response)) {
                    return;
                }

                if (response.status === 422) {
                    var messages = [];
                    for (var k in response.responseJSON) {
                        for (var i in response.responseJSON[k]) {
                            messages.push(response.responseJSON[k][i]);
                        }
                    }
                    layer.alert(messages.join('; '), {icon: 5});
                } else {
                    layer.alert('server error', {icon: 5});
                }
            }
        });
    },
    /**
     * 异步表单提交
     *
     * @param form
     * @param params
     * @param successCallback
     * @param errorCallback 返回true则跳过默认逻辑，返回false则继续执行默认逻辑
     */
    asyncForm: function (form, params, successCallback, errorCallback) {
        successCallback = successCallback || function (data) {};
        errorCallback = errorCallback || function (response) { return false; };
        params = params || {};

        var datas = [];
        for (var key in params) {
            datas.push(key + "=" + params[key]);
        }
        datas.push(form.serialize());

        $.wz.request(
            'post',
            form.attr('action'),
            datas.join('&'),
            successCallback,
            errorCallback
        );
    },

    /**
     * 消息提示
     *
     * @param message
     * @param callback
     * @param icon
     */
    message: function (message, callback, icon) {
        // -1 - default
        // 1 - success
        // 2 - error
        // 3 - question
        // 4 - lock
        // 5 - cry
        // 6 - smile
        // 7 - ！
        icon = icon ||  -1;
        callback = callback || function () {};
        layer.msg(message, {
            icon: icon
        }, callback);
    },

    /**
     * 操作成功提示
     *
     * @param message
     * @param callback
     */
    message_success: function (message, callback) {
        this.message(message, callback, 1);
    },

    /**
     * 操作失败提示
     *
     * @param message
     * @param callback
     */
    message_failed: function (message, callback) {
        this.message(message, callback, 2)
    },

    /**
     * 弹框提示
     *
     * @param message
     * @param callback
     */
    alert: function (message, callback) {
        callback = callback || function() {};
        var index = layer.alert(message, {closeBtn: 0, scrollbar: false}, function () {
            callback();
            layer.close(index);
        })
    },
    /**
     * 选择确认提示
     *
     * @param message
     * @param callback
     * @param callback_cancel
     */
    confirm: function (message, callback, callback_cancel) {
        callback_cancel = callback_cancel || function () {};
        var index = layer.confirm(message, {icon: 3}, function () {
            callback();
            layer.close(index);
        }, callback_cancel);
    },

    /**
     * 动态表单提交
     *
     * @param id
     * @param method
     * @param action
     * @param data
     * @param target
     */
    dynamicFormSubmit: function (id, method, action, data, target) {
        var form = document.createElement('form');

        form.id = id;
        form.method = method;
        form.action = action;
        // 在新页面中打开会被浏览器拦截
        form.target = target || '_self';
        form.style.display = 'none';

        for (var key in data) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];

            form.appendChild(input);
        }

        var token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(token);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    },
    /**
     * 对话框中打开窗口
     *
     * @param layerId
     * @param title
     * @param callback
     */
    dialogOpen: function (layerId, title, callback) {
        var pageLoaded = false;

        layer.open({
            id: layerId,
            type: 2,
            title: title,
            shadeClose: true,
            shade: false,
            maxmin: false,
            area: ['100%', '100%'],
            content: '/blank',
            success: function(layero, index) {
                if (pageLoaded) {
                    return ;
                }

                callback($('#' + layerId + ' iframe').attr('id'));
                pageLoaded = true;
            }
        });
    }
};