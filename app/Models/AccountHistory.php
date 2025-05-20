<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHistory extends Model
{
    protected $fillable = ['bank_account_id',
            'transaction_id'];



    function transaction()  {
        return $this->belongsTo(Transaction::class);
    }

    function bankAccount()  {
        return $this->belongsTo(BankAccount::class);
    }
}
