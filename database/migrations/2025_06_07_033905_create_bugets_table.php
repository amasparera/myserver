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
    // category_id (FK → categories.id)
    // amount              -- batas maksimal
    // start_date
    // end_date
    // created_at
    // updated_at

     */
    public function up(): void
    {
        Schema::create('bugets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade');
            $table->decimal('amount', 15, 2); // batas maksimal
            $table->date('start_date'); // tanggal mulai
            $table->date('end_date'); // tanggal akhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugets');
    }
};
