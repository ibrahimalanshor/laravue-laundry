<?php 

namespace App\Repositories\Packet;

use Illuminate\Contracts\Pagination\Paginator;

interface PacketRepositoryInterface
{

    public function get(int $take = null, string $name = null, string $sort_by = null, string $order_by = 'asc'): Paginator;

}

 ?>