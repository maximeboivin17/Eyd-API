<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'note',
        'volunteer_id',
        'created_by',
        'updated_by',
    ];

//    protected $casts = [
//        'created_by' => 'owner',
//        'updated_by' => 'owner',
//    ];

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

//    public function owner(): BelongsTo
//    {
//        return $this->belongsTo(User::class, 'created_by');
//    }
}
