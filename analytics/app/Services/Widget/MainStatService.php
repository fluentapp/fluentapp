<?php

namespace App\Services\Widget;

use App\Models\Visit;
use App\Models\Visitor;
use Exception;

class MainStatService extends WidgetBase
{

    /**
     * Get the count of unique visitors based on the specified filter.
     *
     * @param array $filterDate The filter option for determining the date range.
     * @return array An associative array containing the count of unique visitors for each date/hour/minute based on the filter.
     *               The array keys represent the dates/hours/minutes, and the values represent the unique user count.
     *
     * @throws \Exception If an invalid filter option is provided.
     */
    public function handle(array $filterData): array
    {
        try {
            $filterDate = $filterData['period'];
            $method = $this->predefinedPeriodFilters[$filterDate] ?? null;

            if ($method && method_exists($this, $method)) {
                return $this->$method($filterData);
            } else
                throw new Exception('Invalid Filter');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function handleDateRangeFilter(array $filterData): array
    {
        try {
            $date = $this->prepareDateFilter($filterData);
            $visitorsCount = Visitor::getVisitorsCountByDateRange($date['from_date'], $date['to_date'], $filterData);
            $pageViewsCount = Visit::getTotalPageViewsByDateRange($date['from_date'], $date['to_date'], $filterData);
            $visitsCount = Visit::getTotalVisitsByDateRange($date['from_date'], $date['to_date'], $filterData);
            $viewsPerVisit = Visit::getAverageViewsPerVisitsByDateRange($date['from_date'], $date['to_date'], $filterData);
            $visitDuration = Visit::getAverageVisitsDurationByDateRange($date['from_date'], $date['to_date'], $filterData);
            $bounceRate = Visit::getAverageBounceRateByDateRange($date['from_date'], $date['to_date'], $filterData);
            return [
                "total_unique_visitors" => $visitorsCount, "total_page_views" => $pageViewsCount,
                "total_visits_count" => $visitsCount, "average_views_per_visit" => $viewsPerVisit,
                "visit_duration" => $visitDuration, "bounce_rate" => $bounceRate
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function handleRealTimeFilter(array $filterData): array
    {
        try {
            $date = $this->prepareDateFilter($filterData);
            $visitorsCount = Visitor::getVisitorsCountForPreviousSec($date['sec'], $filterData);
            $pageViewsCount = Visit::getTotalPageViewsPrevSec($date['sec'], $filterData);
            $visitsCount = Visit::getTotalVisitsPrevSec($date['sec'], $filterData);
            $viewsPerVisit = Visit::getAverageViewsPerVisitsPrevSec($date['sec'], $filterData);
            $visitDuration = Visit::getAverageVisitsDurationPrevSec($date['sec'], $filterData);
            $bounceRate = Visit::getAverageBounceRatePrevSec($date['sec'], $filterData);

            return [
                "total_unique_visitors" => $visitorsCount, "total_page_views" => $pageViewsCount,
                "total_visits_count" => $visitsCount, "average_views_per_visit" => $viewsPerVisit,
                "visit_duration" => $visitDuration, "bounce_rate" => $bounceRate
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
