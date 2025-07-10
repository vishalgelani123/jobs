<?php

namespace App\Models;

use App\Traits\ModelActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ModelActionTrait;

    protected $fillable = ['name', 'email', 'password','role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

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
        $resInquiryMaster = ResInquiryMaster::where('inquiry_created_by_id', $this->id)->count();
        $deleteAction = '';
        if ($resInquiryMaster == 0) {
            $deleteAction = $this->deleteModel(route("users.delete", $this), csrf_token(), "users-table");
        }
        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->editModal($this->id)
            . $deleteAction
            . '</ul></div>';
    }

    public function getUserProfileAttribute()
    {
        if ($this->attributes['user_profile']) {
            return asset('user_profile/' . $this->attributes['user_profile']);
        } else {
            return asset('user_profile/no-profile-image.png');
        }
    }

    public function getInitialsNameAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= substr($word, 0, 1);
        }
        return $initials;
    }

    public function getNameAvatarAttribute()
    {
        $label = 'bg-label-info';
        if ($this->hasRole('admin')) {
            $label = 'bg-label-primary';
        }
        if ($this->hasRole('approver')) {
            $label = 'bg-label-success';
        }
        return '<div class="avatar-wrapper">'
            . '<div class="avatar me-2"><span class="avatar-initial rounded-circle ' . $label . '">' . $this->initials_name . '</span></div>'
            . '</div>';
    }

    public function getRoleWithBgAttribute()
    {
        $label = 'bg-label-info';
        if ($this->hasRole('admin')) {
            $label = 'bg-label-primary';
        }
        if ($this->hasRole('approver')) {
            $label = 'bg-label-success';
        }

        if ($this->hasRole('approver') && $this->hasRole('admin')) {
            $label = 'bg-label-dark';
        }

        if ($this->hasRole('approver') && $this->hasRole('admin')) {
            $role = "Approver With Admin";
        } else {
            $role = isset($this->roles[0]->name) ? $this->roles[0]->name : "";
        }

        return '<span class="badge  ' . $label . '">' . $this->cleanString($role) . '</span>';
    }

    public function cleanString($string)
    {
        return ucwords(str_replace("_", " ", $string));
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function isEmployer()
    {
        return $this->role === 'employer';
    }
}
