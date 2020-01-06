/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */


/**
 * 全局变量、函数
 *
 * 注意，这里面很多方法都是由运行时确定的，用于不同模块之间进行交互
 */
$.global = {
    // swagger:

    // 获取swagger编辑器的本地存储key，不同页面可以设置不同的key，以便为不同的页面分别暂存
    // getSwaggerDraftKey()

    // 下面方法用于在swagger编辑器页面加载时，如果本次存储了上次未编辑完成的文件内容
    // 则自动调用，调用后用于更新swagger编辑器的初始内容
    // updateSwaggerFromDraft(draft, callback)

    /**
     * 更新Swagger编辑器的暂存文件
     *
     * 该方法需要覆盖后实现响应逻辑，由swagger-editor调用
     *
     * @param spec
     */
    updateSwaggerDraft: function (spec) {
    },
    /**
     * 清空暂存的文档
     *
     * 该方法用于清理swagger文档编辑器的暂存文件
     */
    clearDocumentDraft: function () {
    },

    /**
     * 获取文档编辑器中的内容
     *
     * 表单提交时调用，获取页面中编辑器的内容
     */
    getEditorContent: function () {
    },

    /**
     * 更新编辑器内容
     */
    updateEditorContent: function (content) {
    },

    /**
     * 获取文档草稿存储key
     */
    getDraftKey: function () {
    },

    /**
     * 窗口大小调整后触发的事件
     */
    windowResize: function () {
    },
    /**
     * 主面板高度
     */
    panel_height: 0,
    markdownEditor: null,
};

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 超链接触发表单提交事件
    $('[wz-form-submit]').on('click', function (e) {
        e.preventDefault();

        var form = $($(this).data('form'));
        var confirm = $(this).data('confirm');

        if (confirm === undefined) {
            form.submit();
            return false;
        }

        $.wz.confirm(confirm, function () {
            form.submit();
        });
    });

    $('button[data-href]').on('click', function () {
        var method = $(this).data('method') || 'get';
        if (method === '' || method === 'get') {
            window.location.href = $(this).data('href');
        } else {
            $.wz.request(method, $(this).data('href'), {}, function (data) {
                window.location.reload(true);
            });
        }
    });
    
});