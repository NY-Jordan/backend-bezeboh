<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankAccount extends Model
{
    protected $fillable =  ['user_id','account_number', 'balance'];


    function createNewAccount(int|null $user_id = null) : BankAccount {
        do {
            $accountNumber = mt_rand(100000000000, 999999999999);
        } while ($this->where('account_number', $accountNumber)->exists());
        $account  = $this->create([
            'user_id' => $user_id ?? Auth::id(),
            'account_number' => $accountNumber,
            'balance' => 0
        ]);

        return $account ;
    }

    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }

    public function isMyBankAccount(string|null $accountNumber = null, null|int $userId = null)
    {
        return $this->whereAccountNumber($accountNumber ?? $this->account_number)
        ->whereUserId($userId ?? Auth::id())->exists();
    }

    static function findByAccountNumber(string $accountNumber):  BankAccount|null {
        return self::whereAccountNumber($accountNumber)->first();
    }

    function balanceIsSufficient(int $amount):  bool {
        return $this->balance > $amount ;
    }

    function accountHistory()  {
        return $this->belongsTo(AccountHistory::class, 'bank_account_id');
    }
}
