<?php 

namespace App\Repositories\Customer;

use Illuminate\Contracts\Pagination\Paginator;

interface CustomerRepositoryInterface
{

    public function get(int $take = null, string $name = null): Paginator;

}

 ?>