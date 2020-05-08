<?php

class ItemsModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function list(): array
    {
        return $this->select('*')
            ->from('items')
            ->fetch_all();
    }

    public function add(string $name, string $surname): void
    {
        $this->insert(
            'items',
            array(
                'name' => $name,
                'surname' => $surname
            )
        )->execute_query();
    }

    public function edit(int $id, string $name, string $surname): void
    {
        $this->update('items')
            ->set(
                array(
                    'name' => $name,
                    'surname' => $surname
                )
            )
            ->where('id', '=', $id)
            ->execute_query();
    }

    public function delete(int $id): void
    {
        $this->delete_from('items')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute_query();
    }
}
