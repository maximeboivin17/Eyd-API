<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'state',
        'created_by',
        'updated_by',
    ];

    /**
     * Potentiellement inutile, à voir si c'est utile vraiment
     */
    public function potentialVolunteers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'demands_users');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
