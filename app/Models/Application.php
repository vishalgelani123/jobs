<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'cover_letter',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function getApplicationStatusWithBgAttribute()
    {
        if ($this->status == "accepted") {
            return '<span class="badge badge-success">Accepted</span>';
        }
        if ($this->status == "pending") {
            return '<span class="badge badge-warning">Pending</span>';
        }
        if ($this->status == "rejected") {
            return '<span class="badge badge-danger">Rejected</span>';
        }
    }
}