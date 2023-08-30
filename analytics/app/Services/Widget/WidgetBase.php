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
        switch ($filters['period']) {
            case 'today':
                return ['from_date' => $now->copy()->startOfDay(), 'to_date' => $now->copy()->endOfDay()];
                break;
            case 'custom':
                $dateTime = Carbon::createFromFormat('Y-m-d', $filters['date'], $siteTimezone);
                return ['from_date' => $dateTime->copy()->startOfDay(), 'to_date' => $dateTime->copy()->endOfDay()];
                break;
            case 'past_7_days':
                return ['from_date' => $now->copy()->subDays(6)->startOfDay(), 'to_date' => $now->copy()->endOfDay()];
                break;
            case 'past_30_days':
                return ['from_date' => $now->copy()->subDays(29)->startOfDay(), 'to_date' => $now->copy()->endOfDay()];
                break;
            case 'custom_range':
                return ['from_date' => Carbon::createFromFormat('Y-m-d H:i:s', $filters['to_date'], $siteTimezone), 'to_date' => Carbon::createFromFormat('Y-m-d H:i:s', $filters['to_date'], $siteTimezone)];
                break;
            case 'real_time':
                return ['mins' => 30, 'sec' => 30];
                break;
            default:
                throw new Exception('Invalid Date Filter');
                break;
        }
    }

    // @todo Omar to be continued read json array and make sure the filters are valid
    protected function prepareQueryFilter(array $filters)
    {
        return [];
    }
}
