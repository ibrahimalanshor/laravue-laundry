<?php 

namespace App\Repositories\Transaction;

use Illuminate\Contracts\Pagination\Paginator;

use App\Models\Transaction;
use App\Repositories\Repository;

class TransactionRepository extends Repository implements TransactionRepositoryInterface
{

    protected function getModel(): String
    {
        return Transaction::class;
    }

    public function get(int $take = null, string $note = null, bool $workingStatus = null, bool $paymentStatus = null, string $from = null, string $to = null, string $sort_by = null, string $order_by = 'asc'): Paginator
    {
        return $this->model->where('note', 'like', '%'.$note.'%')
            ->when(!is_null($workingStatus), function ($query) use ($workingStatus)
            {
                return $query->whereWorkingStatus($workingStatus);
            })
            ->when(!is_null($paymentStatus), function ($query) use ($paymentStatus)
            {
                return $query->whereWorkingStatus($paymentStatus);
            })
            ->when($from, function ($query) use ($from)
            {
                return $query->whereDate('create_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to)
            {
                return $query->whereDate('create_date', '<=', $to);
            })
            ->when($sort_by, function ($query) use ($sort_by, $order_by)
            {
                return $query->orderBy($sort_by, $order_by);
            })
            ->with(['user', 'packet', 'customer'])->paginate($take);
    }

    public function getLatestNote(string $date): Int
    {
        return $this->model->whereDate('create_date', $date)->latest()->first()->note ?? 0;
    }

    public function find(int $id): Object
    {
        return $this->model->with(['user', 'packet', 'customer'])->findOrFail($id);
    }

    public function findByNote(int $note): Object
    {
        return $this->model->with(['user', 'packet', 'customer'])->whereNote($note)->firstOrFail();
    }

    public function updateWorkingStatus(int $id, bool $status = true): Void
    {
        $this->model->findOrFail($id)->update(['working_status' => $status]);
    }

    public function updatePaymentStatus(int $id, bool $status = true): Void
    {
        $this->model->findOrFail($id)->update(['payment_status' => $status]);
    }

    public function countActive(): Int
    {
        return $this->model->whereWorkingStatus(0)->count();
    }

}

 ?>