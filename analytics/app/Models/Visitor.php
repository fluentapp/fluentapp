<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Visitor extends ModelsEvent
{
    /**
     * Get the unique visitors for each hour of a given date.
     *
     * @param string $date The date for which to retrieve unique visitors count in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve unique visitors
     * @return array An array containing the count of unique visitors for each hour of a given date.
     */
    public static function getVisitorsPerHourPerDate(string $fromdate = '', string $todate = '', array $filters): array
    {
        $visitors =  self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->selectRaw("DATE_FORMAT(CONVERT_TZ(events.updated_at, '+00:00','{$filters['site_timezone_offset']}'), '%H') as hour, COUNT(DISTINCT hash) as unique_visitors_count")
            ->where('events.updated_at', '>=', $fromdate)->where('events.updated_at', '<=', $todate)
            ->groupBy('hour');
        $visitors = self::appendQuery($visitors, $filters);
        return $visitors->get()
            ->pluck('unique_visitors_count', 'hour')
            ->toArray();
    }

    /**
     * Get the unique visitors per day between 2 dates
     *
     * @param string $fromDate The from date for which to retrieve unique visitors count in 'Y-m-d' format.
     * @param string $toDate The to date for which to retrieve unique visitors count in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve unique visitors
     * @return array An array containing the count of unique visitors for each day between range of dates.
     */
    public static function getVisitorsByDateRange(string $fromDate = '', string $toDate = '', array $filters): array
    {
        $visitors = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->selectRaw("DATE(CONVERT_TZ(events.updated_at, '+00:00','{$filters['site_timezone_offset']}')) as date, COUNT(DISTINCT hash) as unique_visitors_count")
            ->where('events.updated_at', '>=', $fromDate)->where('events.updated_at', '<=', $toDate)
            ->groupBy('date');
        $visitors = self::appendQuery($visitors, $filters);
        return $visitors->get()
            ->pluck('unique_visitors_count', 'date')
            ->toArray();
    }

    /**
     * Get the count of unique visitors for each minute of the last $min minutes.
     *
     * @param int $min The previous min which to retrieve unique visitors count in 'H:i' format.
     * @param array $filters The filters for which to retrieve unique visitors
     * @return array An array containing the count of unique visitors for each minute of the last X minutes.
     */
    public static function getVisitorsForPreviousMins(int $min = 30, array $filters): array
    {
        $minutesAgo = Carbon::now()->subMinutes($min);
        $visitors = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->selectRaw("DATE_FORMAT(CONVERT_TZ(events.updated_at, '+00:00','{$filters['site_timezone_offset']}'), '%H:%i') as minute, COUNT(DISTINCT hash) as unique_visitors_count")
            ->where('events.updated_at', '>=', $minutesAgo)
            ->groupBy('minute');
        $visitors = self::appendQuery($visitors, $filters);
        return $visitors->get()->pluck('unique_visitors_count', 'minute')
            ->toArray();
    }

    /**
     * Get the count of unique visitors for Date Range.
     *
     * @param string $fromDate The from date for which to retrieve unique visitors count in 'Y-m-d' format.
     * @param string $toDate The to date for which to retrieve unique visitors count in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve unique visitors
     * @return int The count of unique visitors for the Date Range.
     */
    public static function getVisitorsCountByDateRange(string $fromDate = '', string $toDate = '', array $filters): int
    {
        $visitors = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $visitors = self::appendQuery($visitors, $filters);
        return $visitors->distinct('hash')
            ->count('hash');
    }

    /**
     * Get the count of unique visitors for last 30 sec.
     *
     * @param int $sec The previous sec which retrieve the count of unique visitors.
     * @param array $filters The filters for which to retrieve unique visitors
     * @return int The count of unique visitors for the seconds.
     */
    public static function getVisitorsCountForPreviousSec(int $sec = 1800, array $filters): int
    {
        $secAgo = Carbon::now()->subSeconds($sec);
        $visitors = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $secAgo);
        $visitors = self::appendQuery($visitors, $filters);
        return $visitors->distinct('hash')
            ->count('hash');
    }
}
