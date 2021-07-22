<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};

use App\Models\Transaction;
use App\Repositories\Transaction\TransactionRepository;
use App\Services\Transaction\TransactionService;
use App\Http\Resources\Transaction\{TransactionResource, TransactionCollection};
use App\Http\Requests\Transaction\CreateTransactionRequest;

class TransactionController extends Controller
{

    protected $transactionRepo;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepo = $transactionRepo;
    }

    public function index(Request $request): TransactionCollection
    {
        $transactions = $this->transactionRepo->get($request->take, $request->note, $request->working_status, $request->payment_status, $request->from, $request->to, $request->sort_by, $request->order_by ?? 'asc');

        return new TransactionCollection($transactions);
    }

    public function store(CreateTransactionRequest $request): TransactionResource
    {
        $transaction = Transaction::create($request->all());

        return new TransactionResource($transaction);
    }

    public function show(int $transaction): TransactionResource
    {
        $transaction = $this->transactionRepo->find($transaction);

        return new TransactionResource($transaction);
    }

    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();

        return response()->json(['errors' => 'Transaksi dihapus']);
    }

    public function updateWorkingStatus(int $transaction): JsonResponse
    {
        $this->transactionRepo->updateWorkingStatus($transaction);

        return response()->json(['message' => 'Transaksi diperbarui']);
    }

    public function updatePaymentStatus(int $transaction): JsonResponse
    {
        $this->transactionRepo->updatePaymentStatus($transaction);

        return response()->json(['message' => 'Transaksi diperbarui']);
    }

    public function findByNote(int $note): TransactionResource
    {
        $transaction = $this->transactionRepo->findByNote($note);

        return new TransactionResource($transaction);
    }

    public function getLatestNote(TransactionService $transactionService): JsonResponse
    {
        $note = $transactionService->getLatestNote();

        return response()->json(['data' => $note]);
    }

}
