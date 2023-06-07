<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disability extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'pivot'
    ];

    /**
     * Potentiellement inutile, à voir si c'est utile vraiment
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
