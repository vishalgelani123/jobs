<?php

namespace App\Models;

use App\Traits\ModelActionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Job extends Model
{
    use HasFactory,ModelActionTrait;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'company',
        'location',
        'salary'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function getActionButtonsAttribute()
    {
        if (Auth::user()->role=="candidate"){
            return '<div class="d-inline-block">'
                . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></a>'
                . '<ul class="dropdown-menu dropdown-menu-end m-0">'
                . $this->applyModal($this->id)
                . '</ul></div>';
        } else {
            $deleteAction = $this->deleteModel(route("jobs.delete", $this), csrf_token(), "users-table");
            return '<div class="d-inline-block">'
                . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></a>'
                . '<ul class="dropdown-menu dropdown-menu-end m-0">'
                . $this->editModal($this->id)
                . $deleteAction
                . '</ul></div>';
        }

    }

    public function applyModal($id)
    {
        return '<li><a href="javascript:void(0);" class="dropdown-item" onclick="showApplyFormModal(`' . $id . '`)">Apply</a><li>';
    }
}