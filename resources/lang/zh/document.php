<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

return [
    'page_history'       => '历史文档',
    'creator'            => '创建人',
    'create_time'        => '创建时间',
    'last_modified_user' => '最后修改人',
    'last_modified_time' => '最后修改时间',
    'operation_time'     => '操作时间',
    'modified_user'      => '修改人',
    'document_info'      => '信息',

    'btn_recover' => '还原',
    'title'       => '标题',
    'description' => '描述',
    'content'     => '内容',

    'no_parent_page' => '无上级页面',
    'save_as_draft'  => '加入草稿箱',
    'force_save'     => '强制保存',
    'show_diff'      => '比较差异',

    'document_differ'  => '文档差异对比',
    'latest_document'  => '最新文档',
    'history_document' => '历史版本',
    'read_mode'        => '阅读模式',
    'after_modified'   => '修改后',

    'select_template'        => '选择模板',
    'save_as_template'       => '另存为模板',
    'template_name'          => '模板名称',
    'template_description'   => '模板描述',
    'template_global_access' => '与别人共享',

    'delete_confirm'              => '确定要删除文档“:title”？',
    'recover_confirm'             => '还原后将覆盖当前页面，确定要还原该记录吗？',
    'force_save_confirm'          => '强制保存将可能覆盖其它用户提交的修改，是否确定要强制保存？',
    'differ_dialog_message'       => '如果无法弹出差异对比页面，请在浏览器设置中启用“允许弹出式窗口”选项后重试',
    'document_delete_success'     => '文档删除成功',
    'document_recover_success'    => '文档恢复成功',
    'document_create_info'        => '该项目由 <span class="wz-text-dashed">:username</span> 创建于 <span style="font-weight: bold;">:time</span>。',
    'draft_continue_edit_confirm' => '发现您有尚未保存的内容，是否继续编辑？',

    'validation' => [
        'title_required'       => '文档标题不能为空',
        'title_between'        => '文档标题格式不合法',
        'doc_modified_by_user' => '该页面已经被 :username 于 :time 修改过了',

        'template_name_required'        => '模板名称不能为空',
        'template_name_between'         => '模板名称格式不合法',
        'template_name_template_unique' => '模板名称已经存在',
        'template_content_required'     => '模板内容不能为空',
        'template_description_max'      => '模板描述长度不合法',
    ]
];