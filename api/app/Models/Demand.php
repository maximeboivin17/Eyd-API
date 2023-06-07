<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'state',
        'user_id',
        'event_date',
        'release_date'
    ];

    /**
     * Get the user that owns the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
