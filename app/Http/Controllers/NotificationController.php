<?php
/**
 * Wizard
 *
 * @link      https://aicode.cc/
 * @copyright 管宜尧 <mylxsw@aicode.cc>
 */

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * 用户通知列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lists(Request $request)
    {
        $notifications = \Auth::user()->notifications()->paginate();
        return view('user.notifications', [
            'op'            => 'notification',
            'user'          => \Auth::user(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * 将所有通知标记为已读
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function readAll(Request $request)
    {
        \Auth::user()->unreadNotifications->markAsRead();

        $this->alertSuccess('已全部标记为已读');
        return redirect(wzRoute('user:notifications'));
    }

    /**
     * 标记单条通知为已读
     *
     * @param Request $request
     * @param string  $notification_id
     *
     * @return array
     */
    public function read(Request $request, $notification_id)
    {
        \Auth::user()->unreadNotifications()->where('id', $notification_id)
            ->update(['read_at' => Carbon::now()]);

        return [
            'id' => $notification_id,
        ];
    }
}