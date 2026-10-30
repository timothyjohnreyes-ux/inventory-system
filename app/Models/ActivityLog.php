<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public const LAST_VIEWED_SESSION_KEY = 'activity_logs_last_viewed_id';

    protected $fillable = [
        'user_name',
        'action',
        'description',
        'module',
    ];
}
