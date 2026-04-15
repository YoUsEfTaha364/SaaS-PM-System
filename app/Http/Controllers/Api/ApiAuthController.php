<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Services\api\ApiResponseService;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard("api")->login($user);

        $response = [
           
            'token' => $token,
            'user' => $user
        ];

        return ApiResponseService::response(201, 'Registered successfully', $response);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        $token = auth::guard("api")->attempt($credentials);

        $response = [
            "token" => $token
        ];
        return ApiResponseService::response(200, "loged in successfully", $response);
    }

    public function logout(Request $request)
    {
        $user= Auth::guard("api")->user();
        Auth::guard("api")->logout();

        return ApiResponseService::response(200, "loged out successfully", ["user"=>$user]);

    }
    public function me()
    {
        $user = Auth::guard("api")->user();

        return ApiResponseService::response(200, "", ["user"=>$user]);
    }
    public function refresh()
    {
        $token = Auth::guard("api")->refresh();

        return ApiResponseService::response(200, "created refresh token", ["token"=>$token]);
    }
}
