<?php

namespace App\Http\Controllers;

use App\Repositories\Project;

class HomeController extends Controller
{
    public function home()
    {

        /** @var Project $projects */
        $projects = Project::where('visibility', Project::VISIBILITY_PUBLIC)->get();

        return view('index', ['projects' => $projects]);
    }
}
