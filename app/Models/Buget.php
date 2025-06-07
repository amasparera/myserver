<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buget extends Model
{

    //id (PK)
    // user_id (FK → users.id)
    // category_id (FK → categories.id)
    // amount              -- batas maksimal
    // start_date
    // end_date
    // created_at
    // updated_at

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'start_date',
        'end_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
