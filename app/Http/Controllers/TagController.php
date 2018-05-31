<?php

namespace App\Http\Controllers;


use App\Repositories\Document;
use App\Repositories\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagController extends Controller
{

    /**
     * store the tags
     *
     * @param Request $request
     *
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'p'    => "required|integer",
                'tags' => 'nullable|max:500',
            ],
            [
                'tags.required' => '标签不能为空',
                'tags.between'  => '标签最大不能超过500字符',
            ]
        );

        /** @var Document $page */
        $page = Document::findOrFail($request->input('p'));
        $this->authorize('page-edit', $page);

        $names = array_filter(array_map(function ($val) {
            return trim($val);
        }, explode(',', $request->input('tags'))), function ($val) {
            return !empty($val);
        });

        /** @var Collection $tagsExisted */
        $tagsExisted     = Tag::whereIn('name', $names)->get();
        $tagNamesExisted = array_values($tagsExisted->pluck('name')->map(function ($tag) {
            return strtolower($tag);
        })->toArray());

        $tagsNewCreated = collect($names)->filter(function ($tag) use ($tagNamesExisted) {
            return !in_array(strtolower($tag), $tagNamesExisted);
        })->map(function ($name) {
            return Tag::create(['name' => $name]);
        });

        $tags = $tagsExisted->concat($tagsNewCreated);

        $page->tags()->detach();
        $page->tags()->attach($tags->pluck('id'));

        return $tags->pluck('name', 'id');
    }

}