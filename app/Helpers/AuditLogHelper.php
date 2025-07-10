<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogHelper
{
    public static function storeLog($event, $auditableType, $auditableModelId, $oldValues , $newValues )
    {
        $auditLog = new AuditLog;
        $auditLog->user_id = Auth::id();
        $auditLog->event = $event;
        $auditLog->auditable_type = $auditableType;
        $auditLog->auditable_model_id = $auditableModelId;
        $auditLog->old_values = json_encode($oldValues);
        $auditLog->new_values = json_encode($newValues);
        $auditLog->save();
    }
}
