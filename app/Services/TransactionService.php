<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

 class TransactionService
{
    function newDeposit(int $accountId, int $amount, string|null $description  = null) : Transaction {

        $transaction = Transaction::create([
            'uuid' => Str::uuid(),
            'receiver_id' => $accountId,
            'amount' => $amount,
            'status' => TransactionStatusEnum::SUCCESS,
            'description' => $description,
        ]);

        return $transaction;
    }

    function newTransaction(BankAccount $receiverAccount, BankAccount $senderAccount, int $amount, string|null $description  = null) : Transaction {

        $transaction = Transaction::create([
            'uuid' => Str::uuid(),
            'sender_id' => $senderAccount->id,
            'receiver_id' => $receiverAccount->id,
            'amount' => $amount,
            'status' => TransactionStatusEnum::SUCCESS,
            'description' => $description,
        ]);

        return $transaction;
    }
}

