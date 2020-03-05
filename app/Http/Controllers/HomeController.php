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
use App\Repositories\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    /**
     * 空白页，用于前端兼容
     *
     * @return string
     */
    public function blank()
    {
        return '';
    }

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

        // 基本策略
        // $name为空则表示普通展示，非空则为搜索请求
        // 1. $name非空（搜索请求），所有项目平级展示，不区分目录
        // 2. $name空（普通展示），如果提供了目录id，则只查询目录下的项目，不展示目录列表
        // 3. $name空（普通展示），如果没有提供目录ID，且当前为第一页，则展示不属于任何目录的项目，并且展示目录列表
        // 如果分页不是第一页，则只展示不属于任何项目的目录，不展示目录列表

        /** @var Project $projectModel */
        $projectModel = Project::query();
        $projectModel->withCount('pages');
        if (!empty($name)) {
            $projectModel->where('name', 'like', "%{$name}%");
        } else {
            if (empty($catalogId)) {
                // 首页默认只查询不属于任何目录的项目
                $projectModel->where(function($query) {
                    $query->whereNull('catalog_id')->orWhere('catalog_id', 0);
                });

                // 查询项目目录
                // 在分页查询的第一页之外，不展示目录
                if ($page === 1) {
                    /** @var Collection $catalogs */
                    $catalogs = Catalog::withCount('projects')->where('show_in_home', Catalog::SHOW_IN_HOME)->orderBy('sort_level', 'ASC')->get();
                }
            } else {
                $catalog = Catalog::where('id', $catalogId)->firstOrFail();
                $projectModel->where('catalog_id', intval($catalogId));
            }
        }

        $user = \Auth::user();
        if (!empty($user) && $user->isAdmin() && config('wizard.admin_see_all')) {
            /** @var LengthAwarePaginator $projects */
            $projects = $projectModel->orderBy('sort_level', 'ASC')->paginate($perPage);
        } else {
            $userGroups = empty($user) ? null : $user->groups->pluck('id')->toArray();
            $projectModel->where(function ($query) use ($user, $userGroups) {
                $query->where('visibility', Project::VISIBILITY_PUBLIC);
                if (!empty($userGroups)) {
                    $query->orWhere(function ($query) use ($userGroups) {
                        $query->where('visibility', '!=', Project::VISIBILITY_PUBLIC)
                            ->whereHas('groups', function ($query) use ($userGroups) {
                                $query->where('wz_groups.id', $userGroups);
                            });
                    })->orWhere('user_id', $user->id);
                }
            });

            /** @var LengthAwarePaginator $projects */
            $projects = $projectModel->orderBy('sort_level', 'ASC')->paginate($perPage);
        }

        // 当前用户关注的项目
        // 展示条件：
        // 1. 用户已登录
        // 2. 非搜索请求
        // 3. 页码为第一页
        if (!empty($user) && empty($name) && $page === 1) {
            if (!empty($catalogId)) {
                $favorites =
                    $user->favoriteProjects()->where('catalog_id', $catalogId)->withCount('pages')
                        ->with('catalog')
                        ->get();
            } else {
                $favorites =
                    $user->favoriteProjects()->withCount('pages')->with('catalog')->get();
            }
        }

        // 标签
        if (empty($catalogId)) {
            $tags = Tag::has('pages')->withCount('pages')->orderBy('pages_count', 'desc')->get();
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
            'favorites'  => $favorites ?? [],
            'tags'       => $tags ?? [],
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
