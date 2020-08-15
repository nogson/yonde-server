<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class TalkController extends Controller
{
    public function store(Request $request, Response $response)
    {
        return ['aa' => 'aaa'];
    }
}
