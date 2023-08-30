<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Page extends ModelsEvent
{
    /**
     * Get the Page order by visitors for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Page.
     */
    public static function getPagesVisitorsByDateRange(string $pageCategory = 'entry_page', string $fromDate = '', string $toDate = '', array $filters): array
    {
        $pages = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_page, " . self::MAPPED_FIELDS['pageFields'][$pageCategory] . " as " . $pageCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['pageFields'][$pageCategory])
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_page', 'desc');

        $pages = $pages->where('events.updated_at', '>=', $fromDate)->where('events.updated_at', '<=', $toDate);
        $pages = self::appendQuery($pages, $filters);
        $pages = $pages->get();
        return $pages->toArray();
    }
    /**
     * Get the Page order by visitors real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per Page
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Page.
     */
    public static function getPagesVisitorsPrevSec(string $pageCategory = 'entry_page', int $sec = 30, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $pages = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_page, " . self::MAPPED_FIELDS['pageFields'][$pageCategory] . " as " . $pageCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['pageFields'][$pageCategory])
            ->orderBy('unique_visitors_per_page', 'desc');
        $pages = $pages->where('events.updated_at', '>=', $secondsAgo);
        $pages = self::appendQuery($pages, $filters);
        $pages = $pages->get();
        return $pages->toArray();
    }

    /**
     * Get the Page order by visitors for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Page.
     */
    public static function getTopPagesVisitorsByDateRange(string $fromDate = '', string $toDate = '', array $filters): array
    {
        $pages = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_page, pageviews.page")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy('pageviews.page')
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_page', 'desc');
        $pages = $pages->where('pageviews.created_at', '>=', $fromDate)->where('pageviews.created_at', '<=', $toDate);
        $pages = self::appendQuery($pages, $filters);
        $pages = $pages->get();
        return $pages->toArray();
    }
    /**
     * Get the Page order by visitors for range of dates.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per Page
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Page.
     */
    public static function getTopPagesVisitorsPrevSec(int $sec = 30, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $pages = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_page, pageviews.page")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy('pageviews.page')
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_page', 'desc');
        $pages = $pages->where('pageviews.updated_at', '>=', $secondsAgo);
        $pages = self::appendQuery($pages, $filters);
        $pages = $pages->get();
        return $pages->toArray();
    }
}
