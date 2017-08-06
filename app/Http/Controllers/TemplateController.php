<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Repositories\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * 创建模板
     *
     * @param Request $request
     *
     * @return array
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
                'type'        => 'in:swagger,doc',
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
        $type        = $request->input('type', 'doc');

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
}