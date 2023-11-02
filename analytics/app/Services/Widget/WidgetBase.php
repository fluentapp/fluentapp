<?php

namespace App\Services\Widget;

use Carbon\Carbon;
use Exception;

abstract class WidgetBase
{
    protected $predefinedPeriodFilters = [
        'custom' => 'handleDateRangeFilter',
        'today' => 'handleDateRangeFilter',
        'past_7_days' => 'handleDateRangeFilter',
        'past_30_days' => 'handleDateRangeFilter',
        'real_time' => 'handleRealTimeFilter',
    ];

    abstract public function handle(array $filters): array;
    abstract protected function handleDateRangeFilter(array $filters): array;
    abstract protected function handleRealTimeFilter(array $filters): array;

    protected function prepareDateFilter(array $filters): array
    {
        // Get the site timezone
        $siteTimezone = $filters['site_timezone'];
        // Get the current date and time in the site timezone
        $now = Carbon::now($siteTimezone);
        $siteTimezoneOffsetToMinutes = $this->convertOffsetToMinutes($filters['site_timezone_offset']);
        switch ($filters['period']) {
            case 'today':
                return [
                    'from_date' => $now->copy()->startOfDay()->addMinutes($siteTimezoneOffsetToMinutes),
                    'to_date' => $now->copy()->endOfDay()->addMinutes($siteTimezoneOffsetToMinutes)
                ];
                break;
            case 'custom':
                $dateTime = Carbon::createFromFormat('Y-m-d', $filters['date'], $siteTimezone);
                return [
                    'from_date' => $dateTime->copy()->startOfDay()->addMinutes($siteTimezoneOffsetToMinutes),
                    'to_date' => $dateTime->copy()->endOfDay()->addMinutes($siteTimezoneOffsetToMinutes)
                ];
                break;
            case 'past_7_days':
                return [
                    'from_date' => $now->copy()->subDays(6)->startOfDay()->addMinutes($siteTimezoneOffsetToMinutes),
                    'to_date' => $now->copy()->endOfDay()->addMinutes($siteTimezoneOffsetToMinutes)
                ];
                break;
            case 'past_30_days':
                return [
                    'from_date' => $now->copy()->subDays(29)->startOfDay()->addMinutes($siteTimezoneOffsetToMinutes),
                    'to_date' => $now->copy()->endOfDay()->addMinutes($siteTimezoneOffsetToMinutes)
                ];
                break;
            case 'custom_range':
                $fromDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $filters['from_date'], $siteTimezone);
                $toDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $filters['to_date'], $siteTimezone);

                return [
                    'from_date' => $fromDateTime->copy()->startOfDay()->addMinutes($siteTimezoneOffsetToMinutes),
                    'to_date' => $toDateTime->copy()->endOfDay()->addMinutes($siteTimezoneOffsetToMinutes)
                ];
                break;
            case 'real_time':
                return ['mins' => 30, 'sec' => 1800];
                break;
            default:
                throw new Exception('Invalid Date Filter');
                break;
        }
    }

    /**
     * Converts a timezone offset from "-05:00" or "+05:00" format to minutes.
     *
     * @param string $offset The timezone offset in "-05:00" or "+05:00" format.
     *
     * @return int The timezone offset in minutes, with the appropriate sign (- for negative offsets, + for positive offsets).
     */
    protected function convertOffsetToMinutes(string $offset): int
    {
        $matches = [];
        if (preg_match('/([+-])(\d{2}):(\d{2})/', $offset, $matches)) {
            $sign = ($matches[1] === '-') ? 1 : -1;
            $hours = intval($matches[2]);
            $minutes = intval($matches[3]);
            $totalMinutes = ($hours * 60 + $minutes) * $sign;
            return $totalMinutes;
        }
        // Return 0 if the format is invalid
        return 0;
    }
    // @todo Omar to be continued read json array and make sure the filters are valid
    protected function prepareQueryFilter(array $filters)
    {
        return [];
    }
}
