<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasOneTimePasswords;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'email_verified_at',
        'two_factor_authentication',
        'password',
        'office_id',
        'role_id',
        'verified',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_authentication' => 'boolean',
            'verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function office() { return $this->belongsTo(Office::class); }

    public function getFullNameAttribute(): string { return trim($this->firstName . ' ' . $this->lastName); }
    
    public function role() { return $this->belongsTo(Role::class); }

    public function sentMessages() { return $this->hasMany(Message::class, 'sender_id'); }

    public function receivedMessages() { return $this->hasMany(Message::class, 'receiver_id'); }   

    public function reviews() { return $this->hasMany(Review::class); }

    public function appointments() { return $this->hasMany(Appointment::class); }
}
