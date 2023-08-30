<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Location extends ModelsEvent
{
    /**
     * Get the Location order by visitors for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Location.
     */
    public static function getLocationsVisitorsByDateRange(string $locationCategory = 'countries', string $fromDate = '', string $toDate = '', array $filters): array
    {
        $locations = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_location, " . self::MAPPED_FIELDS['locationFields'][$locationCategory] . " as " . $locationCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['locationFields'][$locationCategory])
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_location', 'desc');

        $locations =  $locations->where('events.updated_at', '>=', $fromDate)->where('events.updated_at', '<=', $toDate);
        $locations = self::appendQuery($locations, $filters);
        $locations = $locations->get();
        return $locations->toArray();
    }
    /**
     * Get the Location  order by visitors real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per Location
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Location.
     */
    public static function getLocationsVisitorsPrevSec(string $locationCategory = 'countries', int $sec = 1800, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $locations = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_location, " . self::MAPPED_FIELDS['locationFields'][$locationCategory] . " as " . $locationCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['locationFields'][$locationCategory])
            ->orderBy('unique_visitors_per_location', 'desc');
        $locations = $locations->where('events.updated_at', '>=', $secondsAgo);
        $locations = self::appendQuery($locations, $filters);
        $locations = $locations->get();
        return $locations->toArray();
    }
}
