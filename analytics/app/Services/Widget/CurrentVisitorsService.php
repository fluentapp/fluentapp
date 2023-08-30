<?php

namespace App\Services\Widget;

use App\Models\Visitor;

use Exception;

class CurrentVisitorsService
{
    /**
     * Get the count of unique visitors
     * 
     * @param array $filterData The filters.
     * @return int coiunt of unique visitors realtime
     *
     * @throws \Exception If an invalid filter option is provided.
     */
    public function handle(array $filterData): int
    {
        try {
            return Visitor::getVisitorsCountForPreviousSec(30, $filterData);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
