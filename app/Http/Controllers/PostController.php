<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function me(Request $request)
    {
        $user = $this->decodeJWT($request);
        $posts = Post::where('user_id', $user->id)->get();
        $posts = $posts->map(function ($post) {
            return collect([
                "id" => $post->id,
                "caption" => $post->caption,
                "photo" => $post->photo,
                "user" => [
                    "id" => $post->user->id,
                    "name" => $post->user->name,
                    "email" => $post->user->email
                ],
                "comments" => $post->comments->map(function ($comment) {
                    return [
                        "text" => $comment->text,
                        "user" => [
                            "id" => $comment->user->id,
                            "name" => $comment->user->name
                        ],
                        "created_at" => Carbon::parse($comment->created_at)->format('d M Y')
                    ];
                }),
                "likes" => $post->likes->map(function ($like) {
                    return [
                        "id" => $like->id,
                        "user" => [
                            "id" => $like->user->id,
                            "name" => $like->user->name
                        ]
                    ];
                }),
                "created_at" => Carbon::parse($post->created_at)->format('d M Y')
            ]);
        });
        return response()->json([
            [
                "status" => 200,
                "data" => $posts,
                "message" => "Post displayed"
            ]
        ]);
    }

    public function index(Request $request)
    {
        $posts = Post::all();
        $posts = $posts->map(function ($post) {
            return collect([
                "id" => $post->id,
                "caption" => $post->caption,
                "photo" => $post->photo,
                "user" => [
                    "id" => $post->user->id,
                    "name" => $post->user->name,
                    "email" => $post->user->email
                ],
                "comments" => $post->comments->map(function ($comment) {
                    return [
                        "text" => $comment->text,
                        "user" => [
                            "id" => $comment->user->id,
                            "name" => $comment->user->name
                        ],
                        "created_at" => $comment->created_at
                    ];
                }),
                "likes" => $post->likes->map(function ($like) {
                    return [
                        "id" => $like->id,
                        "user" => [
                            "id" => $like->user->id,
                            "name" => $like->user->name
                        ]
                    ];
                }),
                "created_at" => Carbon::parse($post->created_at)->format('d M Y')
            ]);
        });

        return response()->json(
            [
                "status" => 200,
                "data" => $posts,
                "message" => "Posts displayed"
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $this->decodeJWT($request);

        $post = new Post();
        $post->caption = $request->caption;
        $post->user_id = $user->id;
        $post->photo = $request->file('photo')->store('photos');
        $post->save();

        return response()->json(
            [
                "status" => 200,
                "data" => [
                    "id" => $post->id,
                    "caption" => $post->caption,
                    "photo" => $post->photo,
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email
                    ]
                ],
                "message" => "Post created"
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        $user = $this->decodeJWT($request);

        if ($user->id !== $post->user->id) {
            return response()->json([
                "status" => 304,
                "data" => [],
                "message" => "Not your resource, redirected to home"
            ]);
        }

        return response()->json(
            [
                "status" => 200,
                "data" => [
                    "id" => $post->id,
                    "caption" => $post->caption,
                    "photo" => $post->photo,
                    "user" => [
                        "id" => $post->user->id,
                        "name" => $post->user->name,
                        "email" => $post->user->email
                    ]
                ],
                "message" => "Post displayed"
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $user = $this->decodeJWT($request);

        $post->caption = $request->caption;
        $post->save();

        return response()->json(
            [
                "status" => 204,
                "data" => [
                    "id" => $post->id,
                    "caption" => $post->caption,
                    "photo" => $post->photo,
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email
                    ]
                ],
                "message" => "Post updated"
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        $user = $this->decodeJWT($request);

        // Storage::disk('public')->delete($post->photo);
        // $post->delete();

        $post->delete();

        return response()->json(
            [
                "status" => 200,
                "data" => [
                    "id" => $post->id,
                    "caption" => $post->caption,
                    "photo" => $post->photo,
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email
                    ]
                ],
                "message" => "Post deleted"
            ]
        );
    }

    private function decodeJWT($request)
    {
        $key = env('JWT_SECRETS');
        $decode = JWT::decode($request->bearerToken(), new Key($key, 'HS256'));
        return $decode;
    }
}
