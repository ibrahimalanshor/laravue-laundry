<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Models\{Packet, Customer, Transaction};
use App\Repositories\Transaction\TransactionRepository;

class DashboardController extends Controller
{

    public function index(TransactionRepository $transactionRepo): JsonResponse
    {
        $data = [
            'totalPacket' => Packet::count(),
            'totalCustomer' => Customer::count(),
            'totalTransaction' => Transaction::count(),
            'totalTransactionActive' => $transactionRepo->countActive()
        ];

        return response()->json(['data' => $data]);
    }

}
