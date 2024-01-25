<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $user = $this->decodeJWT($request);

        $comment = new Comment();
        $comment->text = $request->text;
        $comment->user_id = $user->id;
        $comment->post_id = $post->id;
        $comment->save();

        return response()->json(
            [
                "status" => 200,
                "data" => [
                    $comment
                ],
                "message" => "Comment created"
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function decodeJWT($request)
    {
        $key = env('JWT_SECRETS');
        $decode = JWT::decode($request->bearerToken(), new Key($key, 'HS256'));
        return $decode;
    }
}
