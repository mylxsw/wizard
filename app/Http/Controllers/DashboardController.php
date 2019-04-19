<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use App\Repositories\Catalog;
use App\Repositories\Comment;
use App\Repositories\Document;
use App\Repositories\Group;
use App\Repositories\Project;
use App\Repositories\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // 用户统计
        $userCounts = User::groupBy('role')
            ->select(\DB::raw('role, count(id) as user_count'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item['role'] == User::ROLE_ADMIN ? 'admin' : 'normal' => $item['user_count']
                ];
            })->toArray();
        $groupCount = Group::count();

        // 项目统计
        $projectCounts = Project::groupBy('visibility')
            ->select(\DB::raw('visibility, count(id) as project_count'))
            ->get()
            ->mapWithKeys(function ($item) {
                $visibility =
                    $item['visibility'] == Project::VISIBILITY_PRIVATE ? 'private' : 'public';
                return [
                    $visibility => $item['project_count']
                ];
            })->toArray();
        $catalogCount  = Catalog::count();

        // 文档统计
        $documentCounts = Document::groupBy('type')
            ->select(\DB::raw('type, count(id) as document_count'))
            ->get()
            ->mapWithKeys(function ($item) {
                $type = documentType($item['type']);
                return [
                    $type => $item['document_count']
                ];
            });

        // 评论统计
        $commentCount = Comment::count();


        $documentStat = Document::groupBy(\DB::raw('date_format(created_at, "%Y-%m")'))
            ->select(\DB::raw('date_format(created_at, "%Y-%m") as month, count(id) as document_count'))
            ->where('created_at', '>=', Carbon::now()->addMonth(-10))
            ->orderBy('month', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        return view('admin.dashboard', [
            'op'       => 'dashboard',
            'user'     => [
                'counts'      => $userCounts,
                'group_count' => $groupCount,
            ],
            'project'  => [
                'counts'        => $projectCounts,
                'catalog_count' => $catalogCount,
            ],
            'document' => [
                'counts'        => $documentCounts,
                'comment_count' => $commentCount,
            ],
            'stats'    => [
                'document' => $documentStat,
            ]
        ]);
    }
}