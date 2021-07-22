<?php 

namespace App\Repositories\Packet;

use Illuminate\Contracts\Pagination\Paginator;

use App\Models\Packet;
use App\Repositories\Repository;

class PacketRepository extends Repository implements PacketRepositoryInterface
{

    protected function getModel(): String
    {
        return Packet::class;
    }

    public function get(int $take = null, string $name = null, string $sort_by = null, string $order_by = 'asc'): Paginator
    {
        return $this->model
            ->where('name', 'like', '%'.$name.'%')
            ->when($sort_by, function ($query) use ($sort_by, $order_by)
            {
                return $query->orderBy($sort_by, $order_by);
            })
            ->paginate($take);
    }

}

 ?>