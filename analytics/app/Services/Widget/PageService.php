<?php

namespace App\Services\Widget;

use App\Models\Page;
use Exception;

class PageService extends WidgetBase
{
    protected const PAGE_CATEGORIES = ['top_pages', 'entry_pages', 'exit_pages', 'exit_pages', 'exit_pages'];
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

            if ($method && method_exists($this, $method) && in_array($filterData['page_category'] ?? '', self::PAGE_CATEGORIES)) {
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
            $pageCategory = $filterData['page_category'];
            unset($filterData['page_category']);
            return $pageCategory === 'top_pages'
                ? Page::getTopPagesVisitorsByDateRange($dates['from_date'], $dates['to_date'], $filterData)
                : Page::getPagesVisitorsByDateRange($pageCategory, $dates['from_date'], $dates['to_date'], $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function handleRealTimeFilter(array $filterData): array
    {
        try {
            $sec = $this->prepareDateFilter($filterData)['sec'];
            $pageCategory = $filterData['page_category'];
            unset($filterData['page_category']);
            return $pageCategory === 'top_pages'
                ? Page::getTopPagesVisitorsPrevSec($sec, $filterData)
                : Page::getPagesVisitorsPrevSec($pageCategory, $sec, $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
