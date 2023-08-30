<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class Device extends ModelsEvent
{
    /**
     * Get the Device order by visitors for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Device.
     */
    public static function getDevicesVisitorsByDateRange(string $deviceCategory = 'browsers', string $fromDate = '', string $toDate = '', array $filters): array
    {
        $devices = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_device, " . self::MAPPED_FIELDS['deviceFields'][$deviceCategory] . " as " . $deviceCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['deviceFields'][$deviceCategory])
            ->limit($filters['limit'] ?? 10)
            ->orderBy('unique_visitors_per_device', 'desc');

        $devices = $devices->where('events.updated_at', '>=', $fromDate)->where('events.updated_at', '<=', $toDate);
        $devices = self::appendQuery($devices, $filters);
        $devices = $devices->get();
        return $devices->toArray();
    }
    /**
     * Get the Device order by visitors real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per Device
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by Device.
     */
    public static function getDevicesVisitorsPrevSec(string $deviceCategory = 'browsers', int $sec = 1800, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $devices = self::selectRaw("COUNT(DISTINCT hash) as unique_visitors_per_device, " . self::MAPPED_FIELDS['deviceFields'][$deviceCategory] . " as " . $deviceCategory)
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy(self::MAPPED_FIELDS['deviceFields'][$deviceCategory])
            ->orderBy('unique_visitors_per_device', 'desc');
        $devices = $devices->where('events.updated_at', '>=', $secondsAgo);
        $devices = self::appendQuery($devices, $filters);
        $devices = $devices->get();
        return $devices->toArray();
    }
}
