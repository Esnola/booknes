<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookUser extends Pivot
{
    protected $table = 'book_user';

    public $incrementing = true;

    protected $fillable = [
        'book_id',
        'user_id',
        'status',
        'rating',
        'review',
        'requested_at',
        'borrowed_at',
        'returned_at',
        'return_requested_at',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
