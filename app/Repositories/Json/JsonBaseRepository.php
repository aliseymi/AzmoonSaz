<?php

namespace App\Repositories\Json;

use App\Repositories\Contracts\RepositoryInterface;

class JsonBaseRepository implements RepositoryInterface
{

    public function create(array $data)
    {
        if (file_exists('users.json')) {
            $users = json_decode(file_get_contents('users.json'), true);

            $data['id'] = mt_rand(1, 1000);

            array_push($users, $data);

            file_put_contents('users.json', json_encode($users));
        } else {
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
        $users = json_decode(file_get_contents('users.json'), true);

        foreach ($users as $key => $user) {
            
            if ($user['id'] == $id) {
                
                $user['full_name'] = $data['full_name'] ?? $user['full_name'];
                $user['email'] = $data['email'] ?? $user['email'];
                $user['mobile'] = $data['mobile'] ?? $user['mobile'];
                $user['password'] = $data['password'] ?? $user['password'];

                unset($users[$key]);

                array_push($users, $user);

                if(file_exists('users.json')){
                    unlink('users.json');
                }

                file_put_contents('users.json', json_encode($users));

                break;
            }
        }
    }

    public function delete(array $where)
    {
    }

    public function find(int $id)
    {
    }
}
