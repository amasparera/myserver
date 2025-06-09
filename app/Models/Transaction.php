<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //id (PK)
    // user_id (FK → users.id)
    // account_id (FK → accounts.id)
    // category_id (FK → categories.id)
    // amount             -- nominal uang
    // type               -- enum: 'income' / 'expense' / 'transfer'
    // description (optional)
    // date               -- tanggal transaksi
    // created_at
    // updated_at

    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'amount',
        'description',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
