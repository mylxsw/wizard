<?php

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function store(Request $request)
    {
        $page = Document::findOrFail($request->input('p'));

        $names = $request->input('tags');

        $tags = Tag::whereIn('name', $names)->get();

        dd($tags);
    }

}