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
        'event_date',
        'volunteer_id',
        'disabled_id',
//        'release_date'
    ];

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function disabled(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disabled_id');
    }
}
