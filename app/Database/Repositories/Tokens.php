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
    public function findSingleByEmail(string $email)
    {
        return $this->findSingleBy('email', $email);
    }

    /**
     * retrieves token by token
     *
     * @param  string $token
     * @return mixed
     */
    public function findSingleByToken(string $token)
    {
        return $this->findSingleBy('token', $token);
    }

    /**
     * store token
     *
     * @param  string $email
     * @param  string $token
     * @param  mixed $expires
     * @return int
     */
    public function store(string $email, string $token, $expires = null): int
    {
        return $this->insert([
            'email' => $email,
            'token' => $token,
            'expires' => $expires
        ]);
    }
    
    /**
     * delete token by email
     *
     * @param  string $email
     * @return mixed
     */
    public function deleteByEmail(string $email)
    {
        return $this->deleteBy('email', $email);
    }
}
