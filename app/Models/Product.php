<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
