<?php

namespace App\Http\Controllers;

use App\Events\TransactionCompleted;
use App\Http\Requests\CheckBankAccountRequest;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\makeBankAccountTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\AccountHistory;
use App\Models\BankAccount;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    function __construct(private TransactionService $transactionService) {}

    function store(CreateAccountRequest $request, BankAccount $bankAccount)   {

        $transaction = DB::transaction(function () use ($request, $bankAccount) {

            $newBankAccount = $bankAccount->createNewAccount();

            $transaction  = $this->transactionService->newDeposit(
                $newBankAccount->id,
                $request->deposit_amount,
                'INITIAL_DEPOSIT');


            event(new TransactionCompleted($transaction));

            return  $transaction;
        });

         return response()->json([
                'status' => 201,
                'transaction' =>  TransactionResource::make($transaction)
        ]);
    }

    function makeBankAccountTransaction(makeBankAccountTransactionRequest $request, BankAccount $bankAccount)  {

        abort_if(!$bankAccount->isMyBankAccount($request->sender_account), 400, "the sender's account does not exist");
        abort_if($bankAccount->isMyBankAccount($request->receiver_account), 400, "unable to make a transaction to your own account ");
        //check if balance id  insufficient
        abort_if(
            !$bankAccount->findByAccountNumber($request->sender_account)
            ->balanceIsSufficient($request->amount),
             400,
             "account balance is insufficient"
        );


        $transaction = DB::transaction(function () use ($request, $bankAccount) {

            $transaction  = $this->transactionService->newTransaction(
                $bankAccount->findByAccountNumber($request->receiver_account),
                $bankAccount->findByAccountNumber($request->sender_account),
                $request->amount,
                $request->description)
            ;

            event(new TransactionCompleted($transaction));

            return  $transaction;
        });

         return response()->json([
                'status' => 200,
                'transaction' =>  TransactionResource::make($transaction)
        ]);

    }


    function checkBankAccountBalance(CheckBankAccountRequest $request)  {
        $bankAccount = BankAccount::findByAccountNumber($request->account_number);
        abort_if(!$bankAccount, 404, "bank account not found");
        abort_if(!$bankAccount->isMyBankAccount(), 400, "bank account not found");

        return response()->json([
                'status' => 200,
                'account number' => $bankAccount->account_number,
                'balance' => $bankAccount->balance
        ]);
    }

    function bankAccountHistories(CheckBankAccountRequest $request)  {

        $bankAccount = BankAccount::findByAccountNumber($request->account_number);

        abort_if(!$bankAccount->isMyBankAccount(), 400, "bank account not found");

        $histories = AccountHistory::where('bank_account_id', $bankAccount->id)
        ->get()
        ->map(function ($history) {
            return $history->transaction;
        });

        return response()->json([
                'status' => 200,
                'histories' => TransactionResource::collection($histories),
        ]);

    }

}
