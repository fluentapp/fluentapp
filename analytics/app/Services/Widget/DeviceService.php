<?php

namespace App\Services\Widget;

use App\Models\Device;
use Exception;

class DeviceService extends WidgetBase
{

    protected const DEVICE_CATEGORIES = ['os', 'browsers', 'sizes'];
    /**
     * Get the count of unique visitors based on the specified filter.
     *
     * @param array $filterData The filter option for determining the date range.
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

            if ($method && method_exists($this, $method) && in_array($filterData['device_category'] ?? '', self::DEVICE_CATEGORIES)) {
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
            $deviceCategory = $filterData['device_category'];
            unset($filterData['device_category']);
            return Device::getDevicesVisitorsByDateRange($deviceCategory, $dates['from_date'], $dates['to_date'], $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function handleRealTimeFilter(array $filterData): array
    {
        try {
            $sec = $this->prepareDateFilter($filterData)['sec'];
            $deviceCategory = $filterData['device_category'];
            unset($filterData['device_category']);
            return Device::getDevicesVisitorsPrevSec($deviceCategory, $sec, $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
