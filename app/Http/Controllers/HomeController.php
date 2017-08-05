<?php

namespace App\Http\Controllers;

use App\Repositories\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {

        /** @var Project $projects */
        $projects = Project::where('visibility', Project::VISIBILITY_PUBLIC)->get();

        return view('index', ['projects' => $projects]);
    }

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
        $this->alert(__('common.lang_swatch_success'));

        return redirect(wzRoute('home'));
    }
}
