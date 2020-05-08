<?php

class AdminModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function login(string $username, string $password): bool
    {
        $admin = $this->select('*')
            ->from('admin')
            ->where('username', '=', $username)
            ->fetch();

        return empty($admin) ? false : compare_hash($password, $admin['password']);
    }
}
