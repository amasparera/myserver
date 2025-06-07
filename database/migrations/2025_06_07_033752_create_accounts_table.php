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
    // name              -- contoh: "Dompet", "BNI", "Gopay"
    // initial_balance   -- saldo awal
    // created_at
    // updated_at
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('name'); // contoh: "Dompet", "BNI", "Gopay"
            $table->decimal('initial_balance', 15, 2)->default(0); // saldo awal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
