<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Visit extends ModelsEvent
{
    /**
     * Get the total page views for Date Range.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return int The count of page visits for the Date Range.
     */
    public static function getTotalPageViewsByDateRange(string $fromDate = '', string $toDate = '', array $filters): int
    {
        $pageViewsCount = self::join('pageviews', 'events.id', '=', 'pageviews.event_id');
        $pageViewsCount = $pageViewsCount->where('pageviews.created_at', '>=', $fromDate)
            ->where('pageviews.created_at', '<=', $toDate);
        $pageViewsCount = self::appendQuery($pageViewsCount, $filters);
        return $pageViewsCount->get()->count();
    }
    /**
     * Get the total page views for last $sec seconds.
     *
     * @param int $sec The previous sec which retrieve the total page views
     * @param array $filters The filters for which to retrieve count
     * @return int The count of page visits for last $sec seconds.
     */
    public static function getTotalPageViewsPrevSec(int $sec = 1800, array $filters): int
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $pageViewsCount = self::join('pageviews', 'events.id', '=', 'pageviews.event_id');
        $pageViewsCount = $pageViewsCount->where('pageviews.created_at', '>=', $secondsAgo);
        $pageViewsCount = self::appendQuery($pageViewsCount, $filters);
        return $pageViewsCount->withCount('pageViews')->value('page_views_count') ?? 0;
    }




    /**
     * Get the total visits for Date Range.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return int The count of total visits for the Date Range.
     */
    public static function getTotalVisitsByDateRange(string $fromDate = '', string $toDate = '', array $filters): int
    {
        $visitsCount = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $visitsCount = self::appendQuery($visitsCount, $filters);
        return $visitsCount->distinct('events.id')->count() ?? 0;
    }
    /**
     * Get the total visits for last $sec seconds.
     *
     * @param int $sec The previous sec which retrieve the total visits
     * @param array $filters The filters for which to retrieve count
     * @return int The count of total visits for last $sec seconds.
     */
    public static function getTotalVisitsPrevSec(int $sec = 1800, array $filters): int
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $visitsCount = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $secondsAgo);
        $visitsCount = self::appendQuery($visitsCount, $filters);
        return $visitsCount->distinct('events.id')->count() ?? 0;
    }



    /**
     * Get the average views per visit for Date Range.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return int The average views per visit for the Date Range.
     */
    public static function getAverageViewsPerVisitsByDateRange(string $fromDate = '', string $toDate = '', array $filters): int
    {
        $averageViewsPerVisits = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $averageViewsPerVisits = self::appendQuery($averageViewsPerVisits, $filters);
        return $averageViewsPerVisits->withCount('pageViews')
            ->get()
            ->average('page_views_count') ?? 0;
    }
    /**
     * Get the average views per visit for last $sec seconds.
     *
     * @param int $sec The previous sec which retrieve the average views per visit
     * @param array $filters The filters for which to retrieve count
     * @return int The average views per visit for last $sec seconds.
     */
    public static function getAverageViewsPerVisitsPrevSec(int $sec = 1800, array $filters): int
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $averageViewsPerVisits = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $secondsAgo);
        $averageViewsPerVisits = self::appendQuery($averageViewsPerVisits, $filters);
        return $averageViewsPerVisits->withCount('pageViews')
            ->get()
            ->average('page_views_count') ?? 0;
    }


    /**
     * Get the average visits duration for Date Range.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return int The average visits duration for the Date Range.
     */
    public static function getAverageVisitsDurationByDateRange(string $fromDate = '', string $toDate = '', array $filters): int
    {
        $averageVisitsDuration = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $averageVisitsDuration = self::appendQuery($averageVisitsDuration, $filters);
        return $averageVisitsDuration->groupBy('events.id')->average('duration') ?? 0;
    }
    /**
     * Get the average visits duration for last $sec seconds.
     *
     * @param int $sec The previous sec which retrieve the average visits duration
     * @param array $filters The filters for which to retrieve count
     * @return int The average visits duration for last $sec seconds.
     */
    public static function getAverageVisitsDurationPrevSec(int $sec = 1800, array $filters): int
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $averageVisitsDuration = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $secondsAgo);
        $averageVisitsDuration = self::appendQuery($averageVisitsDuration, $filters);
        return $averageVisitsDuration->groupBy('events.id')->average('duration') ?? 0;
    }


    /**
     * Get the average bounce rate for Date Range.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return float The average bounce rate for the Date Range.
     */
    public static function getAverageBounceRateByDateRange(string $fromDate = '', string $toDate = '', array $filters): float
    {
        $averageBounceRate = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $averageBounceRate = self::appendQuery($averageBounceRate, $filters);
        $averageBounceRate = $averageBounceRate
            ->select('events.id', 'events.is_bounced')
            ->groupBy('events.id', 'events.is_bounced')
            ->get();
        $totalEvents = $averageBounceRate->count();
        $totalBouncedEvents = $averageBounceRate->where('is_bounced', 1)->count();
        return $totalEvents > 0 ? ($totalBouncedEvents / $totalEvents) : 0;
    }
    /**
     * Get the average bounce rate for last $sec seconds.
     *
     * @param int $sec The previous sec which retrieve the average bounce rate
     * @param array $filters The filters for which to retrieve count
     * @return int The float bounce rate for last $sec seconds.
     */
    public static function getAverageBounceRatePrevSec(int $sec = 1800, array $filters): float
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $averageBounceRate = self::join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->where('events.updated_at', '>=', $secondsAgo);
        $averageBounceRate = self::appendQuery($averageBounceRate, $filters);
        $averageBounceRate = $averageBounceRate
            ->select('events.id', 'events.is_bounced')
            ->groupBy('events.id', 'events.is_bounced')
            ->get();
        $totalEvents = $averageBounceRate->count();
        $totalBouncedEvents = $averageBounceRate->where('is_bounced', 1)->count();
        return $totalEvents > 0 ? ($totalBouncedEvents / $totalEvents) : 0;
    }
}
