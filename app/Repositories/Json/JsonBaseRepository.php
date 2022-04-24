<?php

namespace App\Repositories\Json;

use App\Repositories\Contracts\RepositoryInterface;

class JsonBaseRepository implements RepositoryInterface{

    public function create(array $data)
    {
        if(file_exists('users.json')){
            $users = json_decode(file_get_contents('users.json'), true);

            $data['id'] = mt_rand(1, 1000);

            array_push($users, $data);

            file_put_contents('users.json', json_encode($users));
        }else{
            $users = [];

            $data['id'] = mt_rand(1, 1000);

            array_push($users, $data);

            file_put_contents('users.json', json_encode($users));
        }
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