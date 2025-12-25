<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LogReader
{
    /**
     * Parse the laravel.log file for errors.
     */
    public static function getLogs(int $page = 1, int $perPage = 20)
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }

        $logContent = File::get($logPath);

        // Split by timestamp pattern: [2025-12-25 13:44:12]
        $logs = preg_split('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/m', $logContent, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $parsedLogs = [];
        for ($i = 0; $i < count($logs); $i += 2) {
            $timestamp = $logs[$i];
            $entry = $logs[$i + 1] ?? '';

            // Extract environment and level (local.ERROR: ...)
            preg_match('/^ (\w+)\.(\w+): (.*)$/s', $entry, $matches);

            if ($matches) {
                $parsedLogs[] = [
                    'timestamp' => $timestamp,
                    'env' => $matches[1],
                    'level' => $matches[2],
                    'message' => trim($matches[3]),
                ];
            }
        }

        $parsedLogs = array_reverse($parsedLogs);
        $total = count($parsedLogs);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($parsedLogs, $offset, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => \Illuminate\Support\Facades\Request::url(), 'query' => \Illuminate\Support\Facades\Request::query()]
        );
    }
}
