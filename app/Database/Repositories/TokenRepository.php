<?php

namespace App\Database\Repositories;

use App\Database\Models\Token;

class TokenRepository
{
    public function findByEmail(string $email)
    {
        return Token::findBy('email', $email);
    }

    public function findByToken(string $token)
    {
        return Token::findBy('token', $token);
    }

    public function store(string $email, string $token, $expire = null)
    {
        return Token::create([
            'email' => $email,
            'token' => $token,
            'expire' => $expire
        ]);
    }
}
