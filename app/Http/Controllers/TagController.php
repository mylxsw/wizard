<?php

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function store(Request $request)
    {
        $page_id = $request->input('p');
        $tags    = $request->input('tags');
        $this->validateParameters(
            [
                'page_id' => $page_id,
                'tags'    => $tags,
            ],
            [
                'page_id' => "required|integer",
                'tags'    => 'nullable|max:500',
            ],
            [
                'tags.required' => '标签不能为空',
                'tags.between'  => '标签最大不能超过500字符',
            ]
        );
        /** @var Document $page */
        $page  = Document::findOrFail($page_id);
        $names = explode(',', $tags);
        $tags  = [];
        foreach ($names as $name) {
            $tags[] = Tag::firstOrCreate(['name' => $name])->toArray();
        }
        $page->tags()->detach();
        $page->tags()->attach(array_pluck($tags, 'id'));

        return $tags;
    }

}