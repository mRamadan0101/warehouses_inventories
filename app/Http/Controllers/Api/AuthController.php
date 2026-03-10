<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->setCode(401)
                ->setError('Invalid credentials')
                ->send();
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->setCode(200)
            ->setData((new UserResource($user))->setToken($token))
            ->setSuccess('Login successful')
            ->send();
    }
}
