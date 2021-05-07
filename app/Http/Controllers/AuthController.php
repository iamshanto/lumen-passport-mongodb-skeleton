<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required',
            'password' => 'required|min:8'
        ]);

        /** @var User $user */
        $user = User::where('mobile', '01918171625')->get()->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Invalid username']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid password']);
        }
        $token = $user->createToken($user->mobile);

        return ['status' => 'ok', 'token' => $token->accessToken];
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|unique:users',
            'name' => 'required',
            'email' => 'email',
            'password' => 'required|min:8'
        ]);

        // Check if user already exist
        /*if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }*/

        // Create new user
        try {
            $user = new User($request->all());
            $user->password = Hash::make($request->password);
            if ($user->save()) {
                // Will call login method
                //return $this->login($request);
                return response()->json(['status' => 'success', 'message' => 'welcome']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function me()
    {
        return response()->json(['message' => sprintf('Hello %s', auth()->user()->mobile)]);
    }
}
