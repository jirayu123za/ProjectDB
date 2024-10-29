<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $payload = $request->validate([
            "first_name" => "required|min:2|max:25",
            "last_name" => "required|min:2|max:25",
            "email" => "required|email|unique:users,email",
            "user_name" => "required|alpha_num:ascii|min:4|max:50|unique:users,user_name",
            "password" => "required|min:6|max:50|confirmed"
        ]);

        try {
            $payload["password"] = Hash::make($payload["password"]);
            User::create($payload);
            return response()->json([
                "status" => 200,
                "message" => "Account created successfully!"
            ]);
        } catch (\Exception $err) {
            Log::info("user_register_err =>" . $err->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Something went wrong!"],
                500
            );
        }
    }

    public function login(Request $request)
    {
        $payload = $request->validate([
            // "email" => "required|email",
            "user_name" => "required|alpha_num:ascii",
            "password" => "required"
        ]);

        try {
            // $user = User::where("email", $payload["email"])->first();
            $user = User::where("user_name", $payload["user_name"])->first();
            if ($user) {
                if (!Hash::check($payload["password"], hashedValue: $user->password)) {
                    return response()->json([
                        "status" => 401,
                        "message" => "Invalid credentials."
                    ]);
                }

                $token = $user->createToken("web")->plainTextToken;
                $authRes = array_merge($user->toArray(), ["token" => $token]);
                return ["status" => 200,
                        "user" => $authRes,
                        "message" => "Loggedin succssfully!"
                    ];
            }
            return response()->json([
                "status" => 401,
                "message" => "No account found with these credentials."
            ]);
        } catch (\Exception $err) {
            Log::info("user_register_err =>" . $err->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Something went wrong!"],
                500
            );
        }
    }

    public function checkCredentias(Request $request)
    {
        $payload = $request->validate([
            // "email" => "required|email",
            "user_name" => "required|alpha_num:ascii",
            "password" => "required"
        ]);

        try {
            // $user = User::where("email", $payload["email"])->first();
            $user = User::where("user_name", $payload["user_name"])->first();
            if ($user) {
                if (!Hash::check($payload["password"], hashedValue: $user->password)) {
                    return response()->json([
                        "status" => 401,
                        "message" => "Invalid credentials."
                    ]);
                }
                return ["status" => 200, "message" => "Loggedin succssfully!", "user" => $user];
            }
            return response()->json([
                "status" => 401,
                "message" => "No account found with these credentials."
            ]);
        } catch (\Exception $err) {
            Log::info("user_register_err =>" . $err->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Something went wrong!"],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return [
                    "status" => 200, 
                    "message" => "logged out successfully!"
                   ];
        } catch (\Exception $err) {
            Log::info("user_logout_err =>" . $err->getMessage());
            return response()->json([
                "status" => 500, 
                "message" => "Something went wrong!"], 500
            );
        }
    }

}
