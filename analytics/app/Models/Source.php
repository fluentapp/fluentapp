<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Source extends ModelsEvent
{
    /**
     * Get the Source(referrer) order by visitors for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getSourcesVisitorsByDateRange(string $fromDate = '', string $toDate = '', array $filters): array
    {
        $parsedFromDate = Carbon::parse($fromDate)->format('Y-m-d');
        $parsedToDate   =  Carbon::parse($toDate)->format('Y-m-d');
        $sources = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_source, referrer")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy('referrer')
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_source', 'desc');

        $sources = $sources->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $sources = self::appendQuery($sources, $filters);
        $sources = $sources->get();
        return $sources->toArray();
    }
    /**
     * Get the Source(referrer) order by visitors real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per referrer
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getSourcesVisitorsPrevSec(int $sec = 30, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $sources = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_source, referrer")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy('referrer')
            ->orderBy('unique_visitors_per_source', 'desc');

        $sources =  $sources->where('events.updated_at', '>=', $secondsAgo);
        $sources = self::appendQuery($sources, $filters);
        $sources = $sources->get();
        return $sources->toArray();
    }
}
