<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $article = Event::all();
        dd($article);
        return response()->json($article, 201);
    }
}
