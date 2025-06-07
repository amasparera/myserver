<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    //     id (PK)
    // user_id (FK → users.id)
    // name                -- nama pemberi/peminjam
    // amount
    // type                -- 'payable' (saya harus bayar) / 'receivable' (saya diberi pinjam)
    // status              -- 'unpaid' / 'paid'
    // description (optional)
    // due_date
    // created_at
    // updated_at

    protected $fillable = [
        'user_id',
        'name',
        'amount',
        'type',
        'status',
        'description',
        'due_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
