<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Complaint extends Model
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     *
     * Note: we include verification/reference fields used elsewhere in the app.
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'contact',
        'dob',
        'address',
        'type',
        'description',
        'reference',
        'email_verified',
        'verification_token',
        'status'
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'dob' => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function hearing()
    {
        return $this->hasOne(Hearing::class);
    }
}
