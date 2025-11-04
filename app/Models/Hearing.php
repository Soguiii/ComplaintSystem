<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'complainant',
        'complaint_id',
        'contact',
        'scheduled_at',
        'status_changed_at',
        'details',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'status_changed_at' => 'datetime',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
