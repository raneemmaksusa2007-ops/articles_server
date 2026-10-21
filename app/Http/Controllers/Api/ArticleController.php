<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(){
        return Article::latest()->get();
    }
    public function show(Article $article){
        return $article;
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string'
        ]);

        $article = Article::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id'=> $request->user()->id
        ]);

        return response()->json([
            "message" => "Article created Successfuly",
            $article
            ]
            ,201);
    }
    
    public function destroy(Article $article){
        if($article->user_id !== auth()->user()->id){
            return response()->json(['massage'=>'not allowed'],403);
        }
        $article->delete();
        return response()->json(['message'=>'Article was deleted successfuly!'],200);
    }
    public function update(Article $article, Request $request){
        if($article->user_id !== auth()->user()->id){
           return response()->json(['message'=>'You can not update this article !'], 403);
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'body' => 'required|string'
    ]);

    $article->update([
        'title' => $request->title,
        'body' => $request->body,
    ]);

    return response()->json($article, 200);
}
}
