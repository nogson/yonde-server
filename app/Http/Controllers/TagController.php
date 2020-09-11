<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagMap;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('created_at', 'desc')->get();

        $tags = $tags->map(function ($tag) {
            $tag_count = TagMap::where('tag_id', $tag->id)->count();
            return ['id' => $tag->id, 'name' => $tag->name, 'count' => $tag_count];
        });

        return ['tags' => $tags];
    }
}
