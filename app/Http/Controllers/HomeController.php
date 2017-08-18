<?php

namespace App\Http\Controllers;

use App\Repositories\Project;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $user = \Auth::user();
        if (!empty($user) && $user->isAdmin()) {
            /** @var LengthAwarePaginator $projects */
            $projects = Project::paginate($perPage);
        } else {
            /** @var Project $projectModel */
            $projectModel = Project::where('visibility', Project::VISIBILITY_PUBLIC);
            if (!empty($user)) {
                $userGroups   = $user->groups->pluck('id')->toArray();
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
            $projects = $projectModel->paginate($perPage);
        }

        return view('index', ['projects' => $projects->appends(['per_page' => $perPage])]);
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
