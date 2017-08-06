/**
 * Created by mylxsw on 2017/8/4.
 */

/**
 * 创建markdown编辑器
 *
 * @param editor_id
 * @param params
 * @returns {*}
 */
$.wz.mdEditor = function (editor_id, params) {
    var config = {
        // 模板数据源
        template: function () {
            return '';
        },
        templateSelected: function (dialog) {
            return '';
        },
        lang: {
            chooseTemplate: '选择模板',
            confirmBtn: '确定',
            cancelBtn: '取消'
        }
    };

    $.extend(true, config, params);

    return editormd(editor_id, {
        path: "/assets/vendor/editor-md/lib/",
        height: 640,
        taskList: true,
        tex: true,
        flowChart: true,
        sequenceDiagram: true,
        imageUpload    : true,
        imageFormats   : ["jpg", "jpeg", "gif", "png", "bmp"],
        imageUploadURL : "/upload",
        toolbarIcons: function () {
            return ["undo", "redo", "|",
                "bold", "del", "italic", "quote", "|",
                "h1", "h2", "h3", "h4", "h5", "h6", "|",
                "list-ul", "list-ol", "hr", "|",
                "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "pagebreak", "|",
                "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                "template", "|",
                "help", "info"
            ];
        },
        toolbarIconsClass: {
            template: "fa-flask"
        },
        toolbarHandlers: {
            template: function (cm, icon, cursor, selection) {
                this.createDialog({
                    title: config.lang.chooseTemplate,
                    width: 380,
                    height: 300,
                    content: config.template(),
                    mask: true,
                    drag: true,
                    lockScreen: true,
                    buttons: {
                        enter: [config.lang.confirmBtn, function () {

                            cm.replaceSelection(config.templateSelected(this));
                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }],

                        cancel: [config.lang.cancelBtn, function () {
                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }]
                    }
                });
            }
        },
        lang: {
            toolbar: {
                template: config.lang.chooseTemplate
            }
        }
    });
};