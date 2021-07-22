<?php 

namespace App\Repositories\User;

use Illuminate\Contracts\Pagination\Paginator;

use App\Models\User;
use App\Repositories\Repository;

class UserRepository extends Repository implements UserRepositoryInterface
{

    protected function getModel(): String
    {
        return User::class;
    }

    public function get(int $take = null, string $name = null, string $sort_by = null, string $order_by = 'asc'): Paginator
    {
        return $this->model
            ->where('name', 'like', '%'.$name.'%')
            ->whereRole('operator')
            ->when($sort_by, function ($query) use ($sort_by, $order_by)
            {
                return $query->orderBy($sort_by, $order_by);
            })
            ->paginate($take);
    }

    public function find(int $id): User
    {
        return $this->model->whereRole('operator')->findOrFail($id);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): Void
    {
        $this->find($id)->delete();
    }

}

 ?>