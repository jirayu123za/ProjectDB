<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function updateProfileImage(Request $request) {
    //     $payload = $request->validate([
    //         "profile_image" => "required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048"
    //     ]);

    //     try {
    //         $user = $request->user();
    //         $filename = $payload["profile_image"]->store("images_" . $user->id);
    //         User::where("id", $user->id)->update(["profile_image" => $filename]);
    //         return response()->json(["image" => $filename]);
    //     } catch (\Exception $err) {
    //         Log::info("Profile image error =>" . $err->getMessage());
    //         return response()->json([
    //             "message" => "Something went wrong!"], 500
    //         );
    //     }
    // }
    public function updateUserProfile(Request $request)
    {
        $payload = $request->validate([
            "first_name" => "required|string|min:2|max:25",
            "last_name" => "required|string|min:2|max:25",
            "user_name" => "required|string|alpha_num|min:4|max:50|unique:users,user_name," . $request->user()->id, // Unique except for current user
            "email" => "required|email|unique:users,email," . $request->user()->id,
            "password" => "nullable|string|min:6|max:50|confirmed",
            "phone_no" => "nullable|string|max:15",
            "gender" => "nullable|string|in:male,female",
            "address" => "nullable|string|max:255",
            "profile_image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048"
        ]);

        try {
            $user = $request->user();
            if (!empty($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']);
            } else {
                unset($payload['password']);
            }
            if ($request->hasFile('profile_image')) {
                if ($user->profile_image) {
                    Storage::delete($user->profile_image);
                }
                $filename = $request->file('profile_image')->store("images_" . $user->id);
                $payload['profile_image'] = $filename;
            }

            $user->update($payload);

            return response()->json([
                "status" => 200,
                "message" => "Profile updated successfully!",
                "user" => $user
            ]);
        } catch (\Exception $err) {
            Log::error("Profile update error => " . $err->getMessage());
            return response()->json([
                "status" => 500,
                "message" => "Something went wrong!"
            ], 500);
        }
    }
}
