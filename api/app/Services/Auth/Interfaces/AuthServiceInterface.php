<?php

namespace App\Services\Auth\Interfaces;

interface AuthServiceInterface
{
    public function login(array $credentials);
    public function logout();
    public function refresh();
    public function me();
}
