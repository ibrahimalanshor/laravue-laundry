<?php 

namespace App\Repositories\Transaction;

use Illuminate\Contracts\Pagination\Paginator;

interface TransactionRepositoryInterface
{

    public function get(int $take = null, string $note = null, bool $workingStatus = null, bool $paymentStatus = null, string $from = null, string $to = null, string $sort_by = null, string $order_by = 'asc'): Paginator;

    public function getLatestNote(string $date): Int;

    public function find(int $id): Object;

    public function findByNote(int $note): Object;

    public function updateWorkingStatus(int $id, bool $status = true): Void;

    public function updatePaymentStatus(int $id, bool $status = true): Void;

    public function countActive(): Int;

}

 ?>