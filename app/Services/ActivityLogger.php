<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log a generic activity.
     */
    public static function log(string $type, string $action, ?string $tableName = null, ?int $recordId = null, ?array $oldValues = null, ?array $newValues = null, ?array $metadata = null)
    {
        return AuditLog::create([
            'user_id' => Auth::id() ?? 1, // Fallback to ID 1 if system/anon
            'type' => $type,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Specialized log for authentication events.
     */
    public static function auth(string $action, ?array $metadata = null)
    {
        return self::log('auth', $action, 'users', Auth::id(), null, null, $metadata);
    }

    /**
     * Specialized log for artisan commands.
     */
    public static function command(string $command, ?array $metadata = null)
    {
        return self::log('command', $command, null, null, null, null, $metadata);
    }
}
