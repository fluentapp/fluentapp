<?php

namespace App\Services\Widget;

use App\Models\Source;
use Exception;

class SourceService extends WidgetBase
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
            $dates = $this->prepareDateFilter($filterData);
            return Source::getSourcesVisitorsByDateRange($dates['from_date'], $dates['to_date'], $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function handleRealTimeFilter(array $filterData): array
    {
        try {
            $secs = $this->prepareDateFilter($filterData)['sec'];
            return Source::getSourcesVisitorsPrevSec($secs, $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
