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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    /**
     * 公共首页
     *
     * 数据来源：
     *
     * - 普通用户：显示属性为public，同时用户含有分组权限的项目以及当前用户的项目
     * - 管理员：显示所有项目
     *
     * @param Request $request
     * @param int     $catalog
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request, $catalog = 0)
    {
        $perPage   = (int)$request->input('per_page', 20);
        $name      = $request->input('name');
        $page      = (int)$request->input('page', 1);
        $catalogId = (int)$catalog;
        unset($catalog);

        /** @var Project $projectModel */
        $projectModel = Project::query();
        if (!empty($name)) {
            $projectModel->where('name', 'like', "%{$name}%");
        } else {
            if (empty($catalogId)) {
                // 首页默认只查询不属于任何目录的项目
                $projectModel->whereNull('catalog_id');

                // 查询项目目录
                // 在分页查询的第一页之外，不展示目录
                if ($page === 1) {
                    /** @var Collection $catalogs */
                    $catalogs = Catalog::withCount('projects')->orderBy('sort_level', 'ASC')->get();
                }
            } else {
                $catalog = Catalog::where('id', $catalogId)->firstOrFail();
                $projectModel->where('catalog_id', intval($catalogId));
            }
        }

        $user = \Auth::user();
        if (!empty($user) && $user->isAdmin()) {
            /** @var LengthAwarePaginator $projects */
            $projects = $projectModel->orderBy('sort_level', 'ASC')->paginate($perPage);
        } else {
            $projectModel->where('visibility', Project::VISIBILITY_PUBLIC);
            if (!empty($user)) {
                $userGroups = $user->groups->pluck('id')->toArray();
                if (!empty($userGroups)) {
                    $projectModel = $projectModel->orWhere(function ($query) use ($userGroups) {
                        $query->where('visibility', '!=', Project::VISIBILITY_PUBLIC)
                            ->whereHas('groups', function ($query) use ($userGroups) {
                                $query->where('wz_groups.id', $userGroups);
                            });
                    })->orWhere('user_id', \Auth::user()->id);
                }
            }

            /** @var LengthAwarePaginator $projects */
            $projects = $projectModel->orderBy('sort_level', 'ASC')->paginate($perPage);
        }

        return view('index', [
            'projects'   => $projects->appends([
                'per_page' => $perPage,
                'name'     => $name,
                'catalog'  => $catalogId ?? null,
            ]),
            'name'       => $name,
            'catalogs'   => $catalogs ?? [],
            'catalog_id' => $catalogId ?? 0,
            'catalog'    => $catalog ?? null,
        ]);
    }

    /**
     * 语言选择
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lang(Request $request)
    {
        $this->validate(
            $request,
            [
                'l' => 'required|in:zh,en',
            ]
        );

        $lang = $request->input('l');
        $request->session()->put('locale', $lang);

        \App::setLocale($lang);
        $this->alertSuccess(__('common.lang_swatch_success'));

        return redirect(wzRoute('home'));
    }
}
