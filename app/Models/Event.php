<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'from_datetime',
        'to_datetime',
    ];

    public function location(): BelongsTo {
        return $this->belongsTo(Location::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
