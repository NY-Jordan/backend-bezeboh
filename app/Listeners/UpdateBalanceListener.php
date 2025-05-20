<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Models\AccountHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBalanceListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCompleted $event): void
    {
        $transaction = $event->transaction;

        // update balance of sender
        if ($transaction->sender_id) {
            $sender = $transaction->sender;
            $sender->balance -= $transaction->amount;
            AccountHistory::create([
                'bank_account_id' => $sender->id,
                'transaction_id' =>  $transaction->id
            ]);
            $sender->save();
        }

        // update balance of receiver
        $receiver = $transaction->receiver;
        $receiver->balance += $transaction->amount;
        $receiver->save();

        AccountHistory::create([
            'bank_account_id' => $receiver->id,
            'transaction_id' =>  $transaction->id
        ]);
    }
}
