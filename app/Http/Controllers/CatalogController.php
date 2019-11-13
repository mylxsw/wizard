<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Repositories\Catalog;
use App\Repositories\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * 项目目录管理
 *
 * @package App\Http\Controllers
 */
class CatalogController extends Controller
{

    /**
     * 目录列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function catalogs(Request $request)
    {
        return view('catalog.catalogs', [
            'op'            => 'catalogs',
            'catalogs'      => Catalog::withCount('projects')->orderBy('show_in_home', 'desc')->orderBy('sort_level', 'ASC')->get(),
            'catalogs_none' => Project::whereNull('catalog_id')
                ->orWhere('catalog_id', '=', 0)
                ->count(),
        ]);
    }

    /**
     * 新增目录
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'         => 'required|unique:wz_project_catalogs,name',
                'sort_level'   => 'required|integer|between:-999999999,999999999',
                'show_in_home' => 'boolean',
            ],
            [
                'name.required' => '名称不能为空',
                'name.unique'   => '名称已经存在',
            ]
        );

        Catalog::create([
            'name'         => $request->input('name'),
            'sort_level'   => (int)$request->input('sort_level'),
            'user_id'      => \Auth::user()->id,
            'show_in_home' => $request->input('show_in_home') ? Catalog::SHOW_IN_HOME
                : Catalog::NOT_SHOW_IN_HOME,
        ]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:catalogs'));
    }

    /**
     * 显示目录信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request, $id)
    {
        /** @var Catalog $catalog */
        $catalog = Catalog::where('id', $id)->with('projects')->firstOrFail();

        return view('catalog.info', [
            'op'      => 'catalogs',
            'catalog' => $catalog,
        ]);
    }

    /**
     * 更新目录信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'name'         => [
                    'required',
                    Rule::unique('wz_project_catalogs', 'name')->ignore($id),
                ],
                'sort_level'   => 'required|integer|between:-999999999,999999999',
                'show_in_home' => 'boolean',
            ]
        );

        $name       = $request->input('name');
        $sortLevel  = (int)$request->input('sort_level');
        $showInHome = $request->input('show_in_home');

        /** @var Catalog $catalog */
        $catalog               = Catalog::where('id', $id)->firstOrFail();
        $catalog->name         = $name;
        $catalog->sort_level   = $sortLevel;
        $catalog->show_in_home = $showInHome ? Catalog::SHOW_IN_HOME : Catalog::NOT_SHOW_IN_HOME;

        $catalog->save();

        $this->alertSuccess(__('common.operation_success'));

        return redirect()->back();
    }

    /**
     * 删除目录
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function delete(Request $request, $id)
    {
        /** @var Catalog $catalog */
        $catalog = Catalog::where('id', $id)->firstOrFail();

        \DB::transaction(function () use ($catalog) {
            // 更新项目
            Project::where('catalog_id', $catalog->id)->update(['catalog_id' => null]);

            $catalog->delete();
        });

        $this->alertSuccess(__('common.delete_success'));

        return redirect(wzRoute('admin:catalogs'));
    }

    /**
     * 解除目录与项目的关系
     *
     * @param Request $request
     * @param         $id
     * @param         $project_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProject(Request $request, $id, $project_id)
    {
        Project::where('id', $project_id)->where('catalog_id', $id)->update([
            'catalog_id' => null,
        ]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect()->back();
    }
}