<?php

namespace App\Repositories\Auth;

use App\Repositories\Auth\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function attempt(array $credentials)
    {
        return auth('api')->attempt($credentials);
    }

    public function logout()
    {
        return auth('api')->logout();
    }

    public function refresh()
    {
        return auth('api')->refresh();
    }

    public function user()
    {
        return auth('api')->user();
    }

    public function getTTL()
    {
        return auth('api')->factory()->getTTL();
    }
}
