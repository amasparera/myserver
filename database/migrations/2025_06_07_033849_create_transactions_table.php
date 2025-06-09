<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * //id (PK)
    // user_id (FK → users.id)
    // account_id (FK → accounts.id)
    // category_id (FK → categories.id)
    // amount             -- nominal uang
    // description (optional)
    // date               -- tanggal transaksi
    // created_at
    // updated_at
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade');
            $table->decimal('amount', 15, 2); // nominal uang
            $table->string('description')->nullable(); // deskripsi transaksi, opsional
            $table->date('date'); // tanggal transaksi
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
