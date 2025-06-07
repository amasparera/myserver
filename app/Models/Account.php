<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //id (PK)
    // user_id (FK → users.id)
    // name              -- contoh: "Dompet", "BNI", "Gopay"
    // initial_balance   -- saldo awal
    // created_at
    // updated_at
    protected $fillable = [
        'user_id',
        'name',
        'initial_balance',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
