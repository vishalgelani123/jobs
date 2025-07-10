<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Notification extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function inquiry()
    {
        return $this->belongsTo(ResInquiryMaster::class, 'inquiry_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'user_id', 'user_id');
    }
}
