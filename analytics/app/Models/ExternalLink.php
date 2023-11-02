<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class ExternalLink extends ModelsEvent
{
    /**
     * Get the external links for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getExternalLinkClicksByDateRange(string $fromDate = '', string $toDate = '', array $filters): array
    {
        $externalLink = self::selectRaw("COUNT(id) as clicks_per_link, entry_page as link")
            ->leftJoin('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy("entry_page")
            ->limit($filters['limit'] ?? 10)
            ->orderBy('clicks_per_link', 'desc');

        $externalLink = $externalLink->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $externalLink = self::appendQuery($externalLink, $filters, 'external_link');
        $externalLink = $externalLink->get();

        return $externalLink->toArray();
    }
    /**
     * Get the external link real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per referrer
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getExternalLinkClicksPrevSec(int $sec = 1800, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $externalLink = self::selectRaw("COUNT(id) as clicks_per_link, entry_page as link")
            ->leftJoin('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy("entry_page")
            ->orderBy('clicks_per_link', 'desc');

        $externalLink =  $externalLink->where('events.updated_at', '>=', $secondsAgo);
        $externalLink = self::appendQuery($externalLink, $filters, 'external_link');
        $externalLink = $externalLink->get();
        return $externalLink->toArray();
    }
}
