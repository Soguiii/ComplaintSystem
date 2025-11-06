<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Hearing;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'complaint_id',
        'hearing_id',
        'action',
        'ip',
        'user_agent',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function hearing()
    {
        return $this->belongsTo(Hearing::class);
    }
}
