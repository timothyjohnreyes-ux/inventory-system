<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(
        string $action,
        string $description,
        ?string $module = null,
        ?string $userName = null
    ): void {
        ActivityLog::create([
            'user_name' => $userName,
            'action' => $action,
            'description' => $description,
            'module' => $module,
        ]);
    }
}
