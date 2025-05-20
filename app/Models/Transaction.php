<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'uuid',
        'sender_id',
        'receiver_id',
        'amount',
        'status',
        'description',
    ];

    public function sender()
    {
        return $this->belongsTo(BankAccount::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(BankAccount::class, 'receiver_id');
    }

    function accountHistory()  {
        return $this->belongsTo(AccountHistory::class, 'transaction_id');
    }

}
