<?php 

namespace App\Repositories\User;

use Illuminate\Contracts\Pagination\Paginator;

use App\Models\User;

interface UserRepositoryInterface
{

    public function get(int $take = null, string $name = null, string $sort_by = null, string $order_by = 'asc'): Paginator;

    public function find(int $id): User;

    public function update(int $id, array $data): User;

    public function delete(int $id): Void;

}

 ?>