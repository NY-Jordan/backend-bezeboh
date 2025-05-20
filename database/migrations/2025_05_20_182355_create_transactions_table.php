<?php

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('sender_id')->nullable()->constrained('bank_accounts')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('bank_accounts')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('status', array_map(fn($case) => $case->value, TransactionStatusEnum::cases()))
            ->default(TransactionStatusEnum::PENDING->value);            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
