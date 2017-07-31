<?php
/**
 * wizard
 *
 * @link      https://www.yunsom.com/
 * @copyright 管宜尧 <guanyiyao@yunsom.com>
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function home(Request $request, $id)
    {
        return view('project');
    }

}