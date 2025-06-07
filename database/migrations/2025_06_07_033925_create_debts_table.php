<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * //     id (PK)
    // user_id (FK → users.id)
    // name                -- nama pemberi/peminjam
    // amount
    // type                -- 'payable' (saya harus bayar) / 'receivable' (saya diberi pinjam)
    // status              -- 'unpaid' / 'paid'
    // description (optional)
    // due_date
    // created_at
    // updated_at
     */
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('name'); // nama pemberi/peminjam
            $table->decimal('amount', 15, 2); // jumlah utang
            $table->enum('type', ['payable', 'receivable']); // tipe utang: 'payable' (saya harus bayar) / 'receivable' (saya diberi pinjam)
            $table->enum('status', ['unpaid', 'paid']); // status utang: 'unpaid' / 'paid'
            $table->string('description')->nullable(); // deskripsi utang, opsional
            $table->date('due_date'); // tanggal jatuh tempo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
