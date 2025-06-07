<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //id (PK)
    // user_id (FK → users.id)
    // name               -- contoh: "Makanan", "Transportasi"
    // type               -- enum: 'income' / 'expense'
    // icon (optional)    -- bisa berupa nama ikon
    // created_at
    // updated_at

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'icon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
