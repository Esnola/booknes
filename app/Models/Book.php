<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'image',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function currentBorrow()
    {
        return $this->hasOne(BookUser::class)
            ->where('user_id', auth()->id());
    }

    public function reviews() : HasMany
    {
        return $this->hasMany(BookUser::class)
            ->whereNotNull('review');
    }
}
