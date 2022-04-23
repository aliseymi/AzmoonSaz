<?php

namespace App\Repositories\Json;

use App\Repositories\Contracts\RepositoryInterface;

class JsonBaseRepository implements RepositoryInterface{

    public function create(array $data)
    {
        file_put_contents('users.json', json_encode($data));
    }

    public function all(array $where)
    {
        
    }

    public function update(int $id, array $data)
    {
        
    }

    public function delete(array $where)
    {
        
    }

    public function find(int $id)
    {
        
    }
}