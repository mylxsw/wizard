<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use App\Exceptions\ValidationException;
use App\Http\Controllers\Auth\UserActivateChannel;
use App\Repositories\Group;
use App\Repositories\Project;
use App\Repositories\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UserActivateChannel;

    /**
     * 用户列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        $username = $request->input('name');
        $email = $request->input('email');
        $objectguid = $request->input('guid');
        $role = $request->input('role', null);
        $status = $request->input('status', null);

        $userQuery = User::query();
        if (!empty($username)) {
            $userQuery->where('name', 'like', "%{$username}%");
        }

        if (!empty($email)) {
            $userQuery->where('email', '=', $email);
        }

        if (!empty($objectguid)) {
            $userQuery->where('objectguid', '=', $objectguid);
        }

        if (!is_null($role) && $role !== '') {
            $userQuery->where('role', intval($role));
        }

        if (!is_null($status) && $status !== '') {
            $userQuery->where('status', intval($status));
        }

        $users = $userQuery->orderBy('created_at', 'desc')->paginate();

        $queries = [
            'name'   => $username,
            'email'  => $email,
            'guid'   => $objectguid,
            'role'   => $role,
            'status' => $status,
        ];

        return view('user.users', [
            'users' => $users->appends($queries),
            'query' => $queries,
            'op'    => 'users',
        ]);
    }

    /**
     * 用户信息查看
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function user(Request $request, $id)
    {
        /** @var User $user */
        $user = User::where('id', $id)->firstOrFail();
        $subQuery = function ($query) use ($user) {
            $query->where('user_id', $user->id);
        };
        $groupForSelect = Group::whereDoesntHave('users', $subQuery)->get();

        return view('user.user', [
            'user'             => $user,
            'op'               => 'users',
            'group_for_select' => $groupForSelect,
            'tab'              => $request->input('tab'),
        ]);
    }

    /**
     * 用户基本信息配置页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function basic(Request $request)
    {
        $user = \Auth::user();
        return view('user.basic', [
            'op'   => 'basic',
            'user' => $user,
        ]);
    }

    /**
     * 修改用户基本信息
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function basicHandle(Request $request)
    {
        $uid = \Auth::user()->id;
        $this->validate(
            $request,
            [
                'username' => "required|string|max:255|username_unique:{$uid}",
            ]
        );

        $username = $request->input('username');

        \Auth::user()->update([
            'name' => $username,
        ]);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('user:basic'));
    }

    /**
     * 管理员更新用户信息
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        // 禁止在"用户管理"下更新自己的信息
        if ($id == \Auth::user()->id) {
            return redirect()->back();
        }

        $this->validate(
            $request,
            [
                'username' => "required|string|max:255|username_unique:{$id}",
                'role'     => 'required|in:1,2',
                'status'   => 'required|in:0,1,2',
            ]
        );

        $username = $request->input('username');
        $role = $request->input('role');
        $status = $request->input('status');

        $user = User::where('id', $id)->firstOrFail();
        $user->name = $username;
        $user->role = $role;
        $user->status = $status;

        $user->save();

        $this->alertSuccess(__('common.operation_success'));

        return redirect()->back();
    }

    /**
     * 修改密码页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function password(Request $request)
    {
        return view('user.password', [
            'op' => 'password',
        ]);
    }

    /**
     * 修改密码
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function passwordHandle(Request $request)
    {
        $this->validate(
            $request,
            [
                'original_password' => 'required|user_password',
                'password'          => 'required|string|min:6|confirmed'
            ],
            [
                'original_password.required'      => __('passwords.validation.original_password_required'),
                'original_password.user_password' => __('passwords.validation.original_password_unmatch'),
                'password.required'               => __('passwords.validation.new_password_required'),
                'password.string'                 => __('passwords.validation.new_password_invalidate'),
                'password.min'                    => __('passwords.validation.new_password_at_least'),
                'password.confirmed'              => __('passwords.validation.new_password_confirm_failed'),
            ]
        );

        User::where('id', \Auth::user()->id)->update([
            'password' => \Hash::make($request->input('password'))
        ]);

        $this->alertSuccess(__('passwords.change_password_success'));
        return redirect(wzRoute('user:password'));
    }

    /**
     * 用户账户激活
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activate(Request $request)
    {
        try {
            $token = jwt_parse_token($request->input('token'));
            $user_id = $token->getClaim('uid');
            $email = $token->getClaim('email');

            /** @var User $user */
            $user = User::findOrFail($user_id);
            if (!empty($user->email) && $user->email != $email) {
                abort(422, '激活链接中的邮箱地址与用户邮箱地址不匹配');
            }

            if ($user->isDisabled()) {
                abort(403, '用户账号已禁用，无法激活');
            }

            $user->status = User::STATUS_ACTIVATED;
            $user->save();

            $this->alertSuccess('账号激活成功');

        } catch (ValidationException $e) {
            abort(422, '很抱歉！此激活链接已失效');
        }
        return redirect('/');
    }

    /**
     * 发送激活邮件
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendActivateEmail(Request $request)
    {
        $user = \Auth::user();
        if ($user->isActivated() || $user->isDisabled()) {
            abort(422, '不符合发送激活邮件的条件');
        }

        $session = $request->session();
        $lastSendActivateEmailTime = $session->get('send_activate_email');
        // 5分钟内只允许发送一次激活邮件
        $retryDelay = 5 * 60;
        if ($lastSendActivateEmailTime && time() - $lastSendActivateEmailTime <= $retryDelay) {
            $this->alertError(sprintf(
                '请的操作太过频繁，请 %d 分钟后再试',
                (int)(($lastSendActivateEmailTime + $retryDelay - time()) / 60)
            ));
        } else {
            $session->put('send_activate_email', time());

            $this->sendUserActivateEmail($user);
            $this->alertSuccess('激活邮件发送成功');
        }

        return redirect()->back();
    }

    /**
     * 用户批量加入用户组
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function joinGroup(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'groups' => 'required|array',
            ],
            [
                'groups.required' => '您没有选择要加入的用户组',
            ]
        );

        $groups = $request->input('groups');

        $user = User::where('id', $id)->firstOrFail();
        $user->groups()->attach($groups);

        $this->alertSuccess(__('common.operation_success'));

        return redirect(wzRoute('admin:user', ['id' => $id, 'tab' => 'user-group']));
    }

    /**
     * 用户可写的所有项目列表
     *
     * @return Builder[]|Collection|\Illuminate\Support\Collection
     */
    public function projectsCanWrite()
    {
        /** @var User $user */
        $user = \Auth::user();
        if ($user->isAdmin()) {
            $query = Project::query();
        } else {
            $groupIds = $user->groups->pluck('id');
            $query = Project::Where('user_id', $user->id)
                            ->orWhereHas('groups', function (Builder $query) use ($groupIds) {
                                $query->whereIn('group_id', $groupIds)
                                      ->where('privilege', Project::PRIVILEGE_WR);
                            });

        }
        return $query
            ->with('catalog')
            ->get()
            ->map(function (Project $project) {
                return [
                    'id'           => $project->id,
                    'name'         => $project->name,
                    'catalog_id'   => $project->catalog_id,
                    'catalog_name' => $project->catalog->name ?? null,
                ];
            })->sortBy(function ($item) {
                return $item['catalog_id'] ?? 0;
            })->values();
    }
}