<?php

namespace App\Database\Repositories;

use App\Database\Models\User;
use Core\Http\Request;

class UserRepository
{
    public function findByEmail(string $email)
    {
        return User::findBy('email', $email);
    }

    public function findByToken(TokenRepository $tokenRepository, string $token)
    {
        $token = $tokenRepository->findByToken($token);

        if (!$token) {
            return false;
        }

        return $this->findByEmail($token->email);
    }
    
    public function findAllByEmail(string $email)
    {
        return User::where('email', $email)->getAll();
    }
    
    public function store(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash_pwd($request->password)
        ]);
    }
}
