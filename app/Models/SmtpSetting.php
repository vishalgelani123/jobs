<?php

namespace App\Models;

use App\Traits\ModelActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SmtpSetting extends Model
{
    use HasFactory, ModelActionTrait;

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

    public function getActionButtonsAttribute()
    {
        return '<ul class="d-flex pl-0" style="list-style-type: none;">'
            . $this->editModal($this->id)
            . $this->deleteModel(route("smtp-settings.delete", $this), csrf_token(), "smtp-settings-table")
            . '</ul>';
    }
}
