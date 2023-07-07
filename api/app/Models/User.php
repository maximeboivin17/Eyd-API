<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'volunteer',
        'avatar',
        'phone',
        'note',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
    ];

    /**
     * S'il n'est pas volontaire, obtiens le(s) handicap(s) de l'utilisateur
     */
    public function disabilities(): BelongsToMany
    {
        return $this->belongsToMany(Disability::class, 'users_disabilities');
    }

    /**
     * Obtiens tous les avis reçus par l'utilisateur (volontaire) à chaque fois qu'il aide quelqu'un
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'volunteer_id');
    }

    /**
     * S'il n'est pas volontaire, obtiens toutes les demandes faites par l'utilisateur
     */
    public function demands(): HasMany
    {
        return $this->hasMany(Demand::class, 'created_by');
    }

    /**
     * S'il est volontaire, obtiens jspquoi
     */
//    public function applicantDemand(): HasMany
//    {
//        return $this->hasMany(Demand::class, 'volunteer_id');
//    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
