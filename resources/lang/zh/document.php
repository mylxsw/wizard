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

    'btn_recover' => '还原',
    'title'       => '标题',
    'description' => '描述',
    'content'     => '内容',

    'no_parent_page'   => '无上级页面',
    'save_as_template' => '另存为模板',
    'save_as_draft'    => '加入草稿箱',
    'force_save'       => '强制保存文档',
    'show_diff'        => '比较文档差异',

    'document_differ'  => '文档差异对比',
    'latest_document'  => '最新文档',
    'history_document' => '历史版本',
    'after_modified'   => '修改后',

    'select_template' => '选择模板',

    'delete_confirm'           => '确定要删除文档“:title”？',
    'recover_confirm'          => '还原后将覆盖当前页面，确定要还原该记录吗？',
    'force_save_confirm'       => '强制保存将可能覆盖其它用户提交的修改，是否确定要强制保存？',
    'save_confirm'             => '确定要保存文档？',
    'differ_dialog_message'    => '如果无法弹出差异对比页面，请在浏览器设置中启用“允许弹出式窗口”选项后重试',
    'document_delete_success'  => '文档删除成功',
    'document_recover_success' => '文档恢复成功',

    'validation' => [
        'title_required'       => '文档标题不能为空',
        'title_between'        => '文档标题格式不合法',
        'doc_modified_by_user' => '该页面已经被 :username 于 :time 修改过了',
    ]
];