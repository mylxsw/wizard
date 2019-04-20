/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

/**
 * 创建markdown编辑器
 *
 * @param editor_id
 * @param params
 * @returns {*}
 */
$.wz.mdEditor = function (editor_id, params) {
    var editor_table_id = 0;
    var config = {
        // 模板数据源
        template: function () {
            return '';
        },
        templateSelected: function (dialog) {
            return '';
        },

        jsonToTableTemplate: function () {
            editor_table_id = (new Date()).getTime();
            return "<textarea class='form-control json-to-table-editor border' style='width: 100%; height: 277px;' id='json-to-table-editor-" + editor_table_id + "'></textarea>";
        },
        jsonToTableConvert: function (dialog, cm) {
            var jsonContent = $('#json-to-table-editor-' + editor_table_id).val();
            if (jsonContent.trim() === '') {
                return;
            }

            $.wz.request('post', '/tools/json-to-markdown', {content: jsonContent}, function (data) {
                cm.replaceSelection(data.markdown);
                dialog.hide().lockScreen(false).hideMask();
            });
        },
        lang: {
            chooseTemplate: '选择模板',
            jsonToTable: '从json创建表格',
            confirmBtn: '确定',
            cancelBtn: '取消'
        }
    };

    $.extend(true, config, params);

    var mdEditor = editormd(editor_id, {
        path: "/assets/vendor/editor-md/lib/",
        height: 800,
        taskList: true,
        tex: true,
        flowChart: true,
        sequenceDiagram: true,
        imageUpload: true,
        imageFormats: ["jpg", "jpeg", "gif", "png", "bmp"],
        imageUploadURL: "/upload",
        htmlDecode: 'style,script,iframe,sub,sup|on*',
        toolbarIcons: function () {
            return ["undo", "redo", "|",
                "bold", "del", "italic", "quote", "|",
                "h1", "h2", "h3", "h4", "h5", "h6", "|",
                "list-ul", "list-ol", "hr", "|",
                "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "pagebreak", "|",
                "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                "template", "jsonToTable", "|",
                "help", "info"
            ];
        },
        toolbarIconsClass: {
            template: "fa-files-o",
            jsonToTable: "fa-cubes"
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
            },
            jsonToTable: function (cm, icon, cursor, selection) {
                this.createDialog({
                    title: config.lang.jsonToTable,
                    width: 480,
                    height: 400,
                    content: config.jsonToTableTemplate(),
                    mask: true,
                    drag: true,
                    lockScreen: true,
                    buttons: {
                        enter: [config.lang.confirmBtn, function () {
                            config.jsonToTableConvert(this, cm);
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
                template: config.lang.chooseTemplate,
                jsonToTable: config.lang.jsonToTable
            }
        },
        onload: function () {
            editormd.loadPlugin("/assets/vendor/editor-md/plugins/image-handle-paste/image-handle-paste", function () {
                mdEditor.imagePaste();
            });

            $.wz.imageResize('.editormd-preview-container');
        },
        onchange: function () {
            $.wz.imageResize('.editormd-preview-container');
        }
    });

    return mdEditor;
};