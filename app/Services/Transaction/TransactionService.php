<?php 

namespace App\Services\Transaction;

use App\Repositories\Transaction\TransactionRepository;

class TransactionService implements TransactionServiceInterface
{

    protected $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function getLatestNote(): Int
    {
        $id = $this->transactionRepo->getLatestNote(date('Y-m-d'));

        if ($id) {
            $id = (int)substr($id, -3, 3);
        }

        return date('Ymd').sprintf('%03d', $id + 1);
    }

}

 ?>