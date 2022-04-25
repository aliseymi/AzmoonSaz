<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function create(array $data);

    public function all(array $where);

    public function update(int $id, array $data);

    public function deleteBy(array $where);

    public function delete(int $id);

    public function find(int $id);

    public function paginate(string $search = null, int $page, int $pagesize = 10);
}
