<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Source extends ModelsEvent
{
    /**
     * Get the Source(referrer) order by visitors for range of dates.
     *
     * @param string $sourceCategory The from source category searching for (utm term , medium..)
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getSourcesVisitorsByDateRange(string $sourceCategory = 'all_sources', string $fromDate = '', string $toDate = '', array $filters): array
    {
        $sources = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_source, " . self::MAPPED_FIELDS['sourceFields'][$sourceCategory] . " as " .  $sourceCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['sourceFields'][$sourceCategory])
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_source', 'desc');
        $sources = $sources->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        if ($sourceCategory !== 'all_sources') {
            $sources = $sources->whereNotNull(self::MAPPED_FIELDS['sourceFields'][$sourceCategory]);
        }
        $sources = self::appendQuery($sources, $filters);
        $sources = $sources->get();
        return $sources->toArray();
    }
    /**
     * Get the Source(referrer) order by visitors real time.
     *
     * @param string $sourceCategory The from source category searching for (utm term , medium..)
     * @param int $sec The previous seconds which retrieve the total visitors per referrer
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getSourcesVisitorsPrevSec(string $sourceCategory = 'all_sources', int $sec = 1800, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $sources = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_source, " . self::MAPPED_FIELDS['sourceFields'][$sourceCategory] . " as " . $sourceCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['sourceFields'][$sourceCategory])
            ->orderBy('unique_visitors_per_source', 'desc');

        $sources =  $sources->where('events.updated_at', '>=', $secondsAgo);
        $sources = self::appendQuery($sources, $filters);
        $sources = $sources->get();
        return $sources->toArray();
    }
}
