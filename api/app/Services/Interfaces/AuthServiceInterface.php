<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function login(array $credentials);
    public function logout();
    public function refresh();
    public function me();
} 