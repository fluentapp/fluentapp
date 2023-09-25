<?php

namespace App\Models;

use App\Models\Event as ModelsEvent;
use Carbon\Carbon;

class NotFound extends ModelsEvent
{
    /**
     * Get the 404 pages order by count of pages for range of dates.
     *
     * @param string $fromDate The from date in 'Y-m-d' format.
     * @param string $toDate The to date in 'Y-m-d' format.
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getNotFoundVisitorsByDateRange(string $fromDate = '', string $toDate = '', array $filters): array
    {
        $notFound = self::selectRaw("COUNT(id) as error_per_page, entry_page as pages")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy("entry_page")
            ->limit($filters['limit'] ?? 10)
            ->orderBy('error_per_page', 'desc');

        $notFound = $notFound->where('events.updated_at', '>=', $fromDate)
            ->where('events.updated_at', '<=', $toDate);
        $notFound = self::appendQuery($notFound, $filters, 'not_found');
        $notFound = $notFound->get();

        return $notFound->toArray();
    }
    /**
     * Get the 404 pages order by count of pages for range real time.
     *
     * @param int $sec The previous seconds which retrieve the total visitors per referrer
     * @param array $filters The filters for which to retrieve count
     * @return array The count of visitors by referrer.
     */
    public static function getNotFoundVisitorsPrevSec(int $sec = 1800, array $filters): array
    {
        $secondsAgo = Carbon::now()->subSeconds($sec);
        $notFound = self::selectRaw("COUNT(id) as error_per_page, entry_page as pages")
            ->join('pageviews', 'events.id', '=', 'pageviews.event_id')
            ->groupBy("entry_page")
            ->orderBy('error_per_page', 'desc');

        $notFound =  $notFound->where('events.updated_at', '>=', $secondsAgo);
        $notFound = self::appendQuery($notFound, $filters, 'not_found');
        $notFound = $notFound->get();
        return $notFound->toArray();
    }
}
