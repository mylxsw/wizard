<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Template;
use Illuminate\Http\Request;
use Auth;

class TemplateController extends Controller
{
    /**
     * 创建模板
     *
     * @param Request $request
     *
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'        => 'required|between:1,255|template_unique',
                'content'     => 'required',
                'description' => 'max:255',
                'scope'       => 'in:1,2',
                'type'        => 'in:swagger,markdown',
            ],
            [
                'name.required'        => __('document.validation.template_name_required'),
                'name.between'         => __('document.validation.template_name_between'),
                'name.template_unique' => __('document.validation.template_name_template_unique'),
                'content.required'     => __('document.validation.template_content_required'),
                'description.max'      => __('document.validation.template_description_max'),
            ]
        );

        $name        = $request->input('name');
        $content     = $request->input('content');
        $description = $request->input('description', '');
        $scope       = $request->input('scope', Template::SCOPE_PRIVATE);
        $type        = $request->input('type', 'markdown');

        // 如果创建的是全局项目，则需要检查是否有权限进行创建
        if ($scope == Template::SCOPE_GLOBAL) {
            $this->authorize('template-global-create');
        }

        $template              = new Template();
        $template->name        = $name;
        $template->content     = $content;
        $template->description = $description;
        $template->scope       = $scope;
        $template->type        = $type == 'swagger' ? Template::TYPE_SWAGGER : Template::TYPE_DOC;
        $template->user_id     = \Auth::user()->id;

        $template->save();

        return [
            'id' => $template->id
        ];
    }

    /**
     * 用户模板管理
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function all()
    {
        $templates = Template::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.templates', ['templates' => $templates, 'op' => 'templates']);
    }


    /**
     * 删除模板
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function deleteTemplate($id)
    {
        /** @var Template $template */
        $template = Template::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        $template->delete();

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('user:templates'));
    }

    /**
     * 模板编辑页面
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        /** @var Template $template */
        $template = Template::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        return view('user.templates_edit', ['template' => $template, 'op' => 'templates']);
    }

    /**
     * 保存对模板的修改
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function editHandle(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name'        => "required|between:1,255|template_unique:{$id}",
                'content'     => 'required',
                'description' => 'max:255',
                'scope'       => 'in:1,2',
            ],
            [
                'name.required'        => __('document.validation.template_name_required'),
                'name.between'         => __('document.validation.template_name_between'),
                'name.template_unique' => __('document.validation.template_name_template_unique'),
                'content.required'     => __('document.validation.template_content_required'),
                'description.max'      => __('document.validation.template_description_max'),
            ]
        );

        $name        = $request->input('name');
        $content     = $request->input('content');
        $description = $request->input('description', '');
        $scope       = $request->input('scope');

        /** @var Template $template */
        $template = Template::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        // 如果创建的是全局项目，则需要检查是否有权限进行创建
        if ($template->scope != $scope) {
            $this->authorize('template-global-create');
            $template->scope = ($scope == Template::SCOPE_GLOBAL ? Template::SCOPE_GLOBAL
                : Template::SCOPE_PRIVATE);
        }

        $template->name        = $name;
        $template->description = $description;
        $template->content     = $content;

        $template->save();

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('user:templates:edit', ['id' => $id]));
    }

}