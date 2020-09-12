<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagMap;
use App\models\Talk;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TalkController extends Controller
{

    public function index()
    {
        $talks = Talk::orderBy('created_at', 'desc')->get()->map(function ($item, $key) {
            $contents = explode(',', $item->contents);
            $voice_types = explode(',', $item->voice_types);
            $rates = explode(',', $item->rates);
            $avatars = explode(',', $item->avatars);

            $talk = [
                'id' => $item->id,
                'theme' => $item->theme,
                'tags' => $item->tags->map(function ($tag) {
                    $t = Tag::find($tag->tag_id);
                    return [
                        'id' => $t->id,
                        'name' => $t->name
                    ];
                }),
                'play_count' => $item->play_count,
                'like_count' => $item->like_count,
                'comment_count' => $item->comment_count,
            ];
            foreach ($contents as $index => $value) {
                $talk['comments'][$index] = [];
                $talk['comments'][$index]['content'] = $value;
                $talk['comments'][$index]['voice_type'] = intval($voice_types[$index]);
                $talk['comments'][$index]['rate'] = intval($rates[$index]);
                $talk['comments'][$index]['avatar'] = intval($avatars[$index]);

            }
            return $talk;
        });


        return ['talks' => $talks];
    }

    public function store(Request $request, Response $response)
    {

        return DB::transaction(function () use ($request) {

            $theme = $request->theme;
            $contents = array_column($request->comments, 'content');
            $rates = array_column($request->comments, 'rate');
            $voice_types = array_column($request->comments, 'voice_type');
            $avatars = array_column($request->comments, 'avatar');
            $tags = $request->tags;


            $talk = Talk::create([
                'theme' => $theme,
                'contents' => implode(',', $contents),
                'rates' => implode(',', $rates),
                'voice_types' => implode(',', $voice_types),
                'avatars' => implode(',', $avatars)
            ]);

            if(!empty($tags)) {
                foreach ($tags as $tag) {

                    $t = Tag::where('name', $tag)->first();
                    if (is_null($t)) {
                        $t = Tag::create([
                            'name' => $tag
                        ]);
                    }

                    TagMap::create([
                        'talk_id' => $talk->id,
                        'tag_id' => $t->id
                    ]);
                };
            }

            return $talk;

        });


    }

    public function getTalkByTag(Request $request)
    {

        $talks = Talk::with('tags')->get();


        $talks = $talks->filter(function ($value) use ($request) {
            $tags = $value['tags']->where('tag_id', $request->id)->first();
            if (isset($tags)) {
                return $value;
            };
        });

        $items = $talks->map(function ($talk) {
            $tags = $talk->tags;
            $tags = $tags->map(function ($tag) {
                $t = Tag::find($tag->tag_id);
                return [
                    'id' => $t->id,
                    'name' => $t->name
                ];
            });

            $comments = [];
            $contents = explode(',',$talk->contents);
            $voice_types = explode(',', $talk->voice_types);
            $rates = explode(',', $talk->rates);
            $avatars = explode(',', $talk->avatars);

            foreach ($contents as $index => $value) {
                $comments[$index] = [];
                $comments[$index]['content'] = $value;
                $comments[$index]['voice_type'] = intval($voice_types[$index]);
                $comments[$index]['rate'] = intval($rates[$index]);
                $comments[$index]['avatar'] = intval($avatars[$index]);
            };

            return [
                'id' => $talk->id,
                'theme' => $talk->theme,
                'comments' => $comments,
                'play_count' => $talk->play_count,
                'like_count' => $talk->like_count,
                'comment_count' => $talk->comment_count,
                'tags' => $tags
            ];
        });

        return ['talks' => $items];

    }

    public function addPlayCount(Request $request)
    {

        $talk = Talk::find($request->id);
        $talk->play_count += 1;
        $talk->save();

        return $talk;
    }

}
