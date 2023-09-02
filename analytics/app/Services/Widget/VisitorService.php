<?php

namespace App\Services\Widget;

use App\Models\Visitor;
use Carbon\Carbon;
use Exception;

class VisitorService extends WidgetBase
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
        return in_array($filterData['period'], ['today', 'custom']) ? $this->hoursPerDay($filterData) :  $this->datesPerRange($filterData);
    }

    protected function handleRealTimeFilter(array $filterData): array
    {
        $dateLabels = $chartData = [];
        try {
            $siteTimezone = $filterData['site_timezone'];
            $now = Carbon::now($siteTimezone);
            $mins = $this->prepareDateFilter($filterData)['mins'];
            $visitors = Visitor::getVisitorsForPreviousMins($mins, $filterData);
            $startTime = $now->copy()->subMinutes($mins);
            $endTime = $now->copy();
            while ($endTime >= $startTime) {
                $formattedTime = $endTime->format('H:i');
                $chartData[] = $visitors[$formattedTime] ?? 0;
                $endTime->subMinutes(1);
                $dateLabels[] = "-" . $mins . "m";
                $mins--;
            }
            // Reverse the array so that it starts with the oldest data and ends with the latest
            $chartData = array_reverse($chartData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ["interval" => 'minute', "labels" => $dateLabels, "plot" => $chartData];
    }

    private function hoursPerDay(array $filterData): array
    {
        $dateLabels = $chartData = [];
        try {
            $date = $this->prepareDateFilter($filterData);
            $visitors = Visitor::getVisitorsPerHourPerDate($date['from_date'], $date['to_date'], $filterData);
            $currentTime = Carbon::now($filterData['site_timezone']);
            for ($hour = 0; $hour < 24; $hour++) {
                $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $time = Carbon::createFromTime($hour, 0, 0, $filterData['site_timezone']);
                $dateLabels[] = $time->format('g A'); // Format the time ("1 AM", "2 PM")
                // Check if the time is in the future compared to the current time
                $chartData[] = !empty($visitors[$formattedHour]) ? $visitors[$formattedHour] : ($time->gt($currentTime) ? null : 0);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ["interval" => 'hour', "labels" => $dateLabels, "plot" => $chartData];
    }

    private function datesPerRange(array $filterData): array
    {

        $dateLabels = $chartData = [];
        try {
            $dates = $this->prepareDateFilter($filterData);
            $visitors = Visitor::getVisitorsByDateRange($dates['from_date'], $dates['to_date'], $filterData);
            $siteTimezone = $filterData['site_timezone'];
            $now = Carbon::now($siteTimezone);
            $days = $filterData['period'] === 'past_30_days' ? 30 : 6;
            for ($day = $days; $day >= 0; $day--) {
                $date = $now->copy()->subDays($day);
                $chartData[] = $visitors[$date->format('Y-m-d')] ?? 0;
                $dateLabels[] = $date->format('M d'); // ("Jul 30")
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return ["interval" => 'date', "labels" => $dateLabels, "plot" => $chartData];
    }
}
