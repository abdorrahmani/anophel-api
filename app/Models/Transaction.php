<?php

namespace App\Models;

use App\Enum\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class
        ];
    }


}
