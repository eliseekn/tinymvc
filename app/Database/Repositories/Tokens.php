<?php

namespace App\Database\Repositories;

use Core\Database\Repository;

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
        return $this->findOneWhere('email', $email);
    }

    /**
     * retrieves token by token
     *
     * @param  string $token
     * @return mixed
     */
    public function findOneByToken(string $token)
    {
        return $this->findOneWhere('token', $token);
    }

    /**
     * store token
     *
     * @param  string $email
     * @param  string $token
     * @param  mixed $expire
     * @param  bool $api
     * @return int
     */
    public function store(string $email, string $token, bool $api = false, $expire = null): int
    {
        return $this->insert([
            'email' => $email,
            'token' => $token,
            'expire' => $expire,
            'api' => (int) $api
        ]);
    }
    
    /**
     * delete token by value
     *
     * @param  string $token
     * @param  bool $api
     * @return void
     */
    public function flush(string $token, bool $api = false): void
    {
        $this->delete()
            ->where('token', $token)
            ->and('api', (int) $api)
            ->execute();
    }
}
