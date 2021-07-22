<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['note', 'weight', 'create_date', 'finish_date', 'tax', 'discount', 'total', 'payment_status', 'working_status', 'packet_id', 'customer_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }
}
