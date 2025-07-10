<?php

namespace App\Models;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = ['customer_id', 'sale_date', 'total_amount'];

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
