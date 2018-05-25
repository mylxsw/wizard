<?php

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function store(Request $request)
    {
        /** @var Document $page */
        $page = Document::findOrFail($request->input('p'));
        $names = explode(',', $request->input('tags'));
        $tags = [];
        foreach ($names as $name) {
            $tags[] = Tag::firstOrCreate(['name' => $name])->toArray();
        }
        $page->tags()->detach();
        $page->tags()->attach(array_pluck($tags, 'id'));

        return $tags;
    }

}