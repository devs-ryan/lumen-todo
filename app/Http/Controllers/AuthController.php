<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new user
     * Return the user details and api_token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $this->validate($request, [
            'username' => 'required|unique:users|between:4,12|alpha_dash',
            'password' => 'required|string|between:8,12'
        ]);

        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60)
            ]);

            return $this->apiResponse([
                'action' => 'register',
                'user' => $user
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }
    }

    /**
     * Attempt to login a user with username and password
     * Return the user details and api_token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            $user = User::where('username', $request->username)->first();
            if (!$user) {
                throw new Exception('The username provided is does not exist in our records', 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('The password provided is not valid', 403);
            }

            return $this->apiResponse([
                'action' => 'login',
                'user' => $user
            ]);
        }
        catch(Exception $e) {
            return $this->apiError($e);
        }
    }
}
