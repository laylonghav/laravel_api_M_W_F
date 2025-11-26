<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    //Register

    //   'name',
    //   'email',
    //   'password',
    //    "role"

    public function register(Request $req)
    {
        $req->validate([
            "name" => "required|string|min:3|max:255",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|confirmed",
            "role" => "required|string"
        ]);


        $user = User::create([
            "name" => $req->name,
            "email" => $req->email,
            "password" => Hash::make($req->password),
            "role" => $req->role,
        ]);

        return response()->json([
            "user" => $user,
            "message" => "Created user successfully."
        ], 201);
    }


    //Login
    public function login(Request $req)
    {
        $req->validate([
            "email" => "required|email",
            "password" => "required|string|min:6",
        ]);

        $user = User::where("email", $req->email)->first();

        if (!$user) {
            return response()->json([
                "message" => "User is no found."
            ], 404);
        }

        if (!Hash::check($req->password, $user->password)) {
            return response()->json([
                "message" => "Incorrect password."
            ], 403);
        }

        $tokenResult = $user->createToken("authtoken");
        $token = $tokenResult->plainTextToken;


        $cookie = cookie(
            "auth_token",
            $token,
            60 * 24 * 7,
            "/",
            null,
            true,
            true,
            false,
            "Strict"
        );

        return response()->json([
            "user" => $user,
            "token" => $token,
            "message" => "User is logged in successfully."
        ], 200)->withCookie($cookie);
    }

    //Logout
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget("auth_token");

        return response()->json([
            "message" => "User is logged out successfully."
        ], 200)->withCookie($cookie);
    }

    //me 
    public function me(Request $req)
    {
        $user = $req->user();

        return response()->json([
            "user" => $user,
            "message" => "Get user successfully."
        ], 200);
    }
}
