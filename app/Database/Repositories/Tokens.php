<?php

namespace App\Database\Repositories;

use Framework\Database\Repository;

class Tokens extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'tokens';

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
     * retrieves token by email
     *
     * @param  string $email
     * @return mixed
     */
    public function findOneByEmail(string $email)
    {
        return $this->findOneBy('email', $email);
    }

    /**
     * retrieves token by token
     *
     * @param  string $token
     * @return mixed
     */
    public function findOneByToken(Users $users, string $token)
    {
        $token = $this->findOneBy('token', $token);

        if (!$token) {
            return false;
        }

        return $users->findOneByEmail($token->email);
    }

    /**
     * store token
     *
     * @param  string $email
     * @param  string $token
     * @param  mixed $expire
     * @return int
     */
    public function store(string $email, string $token, $expire = null): int
    {
        return $this->insert([
            'email' => $email,
            'token' => $token,
            'expire' => $expire
        ]);
    }
    
    /**
     * delete token by value
     *
     * @param  string $token
     * @return bool
     */
    public function flush(string $token): bool
    {
        return $this->deleteBy('token', $token);
    }
}
