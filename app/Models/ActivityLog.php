<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'description', 'ip_address', 
        'method', 'url', 'status_code', 'user_agent'
    ];

    // Relasi ke User (siapa yang melakukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}