<?php

namespace App\Repositories\Auth\Interfaces;

interface AuthRepositoryInterface
{
    public function attempt(array $credentials);
    public function logout();
    public function refresh();
    public function user();
    public function getTTL();
}
