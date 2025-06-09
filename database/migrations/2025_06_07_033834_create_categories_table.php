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
    // name               -- contoh: "Makanan", "Transportasi"
    // type               -- enum: 'income' / 'expense' / 'transfer'
    // icon (optional)    -- bisa berupa nama ikon
    // created_at
    // updated_at
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('name'); // contoh: "Makanan", "Transportasi"
            $table->enum('type', ['income', 'expense', 'transfer']); // tipe kategori
            $table->string('icon')->nullable(); // bisa berupa nama ikon, opsional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
