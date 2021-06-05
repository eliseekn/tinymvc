<?php

namespace App\Database\Repositories;

use Core\Http\Request;
use Core\Database\Repository;

class Users extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'users';

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }
    
    /**
     * retrieves user by email
     *
     * @param  string $email
     * @return mixed
     */
    public function findOneByEmail(string $email)
    {
        return $this->findOneWhere('email', $email);
    }

    /**
     * retrieves user by token
     *
     * @param  \App\Database\Repositories\Tokens $tokens
     * @param  string $token
     * @return mixed
     */
    public function findOneByToken(Tokens $tokens, string $token)
    {
        $token = $tokens->findOneByToken($token);

        if (!$token) {
            return false;
        }

        return $this->findOneByEmail($token->email);
    }
    
    /**
     * retrieves users by email
     *
     * @param  string $email
     * @return array
     */
    public function findAllByEmail(string $email): array
    {
        return $this->findAllWhere('email', 'like', $email);
    }
    
    /**
     * store user
     *
     * @param  \Core\Http\Request $request
     * @return int
     */
    public function store(Request $request): int
    {
        return $this->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => hash_pwd($request->password)
        ]);
    }
}
