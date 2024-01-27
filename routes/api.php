<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\ApiAuth;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', function (Request $request) {

    $validated = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required',
        'password' => 'required'
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = $request->password;
    $user->save();

    return response()->json([
        "status" => 200,
        "data" => [
            "name" => $user->name,
            "email" => $user->email,
            "password" => $user->password
        ],
        "message" => "User created"
    ]);
});

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json([
            "status" => 404,
            "data" => [],
            "message" => "User not found"
        ]);
    }

    $passwordMatch = Hash::check($request->password, $user->password);
    if (!$passwordMatch) {
        return response()->json([
            "status" => 404,
            "data" => [],
            "message" => "User not found"
        ]);
    }

    // generate token
    $key = env('JWT_SECRETS');
    $payload = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
    ];

    $jwt = JWT::encode($payload, $key, 'HS256');

    return response()->json([
        "status" => 200,
        "data" => [
            ...$payload,
            "token" => $jwt
        ],
        "message" => "User login"
    ]);
});

Route::apiResource('posts', PostController::class)->only('index');
Route::apiResource('posts', PostController::class)->except('index')->middleware(ApiAuth::class);

Route::get('/user/posts/', [PostController::class, 'me']);

Route::apiResource('posts.comments', CommentController::class)->only('store');

Route::apiResource('posts.likes', LikeController::class)->only('store', 'destroy');
