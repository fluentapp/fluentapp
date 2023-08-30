<?php

namespace App\Domain\DailyHash\Service;

use Illuminate\Support\Facades\Log;
use App\Domain\DailyHash\Repository\DailyHashRepository;

class DailyHashFinder {

    private DailyHashRepository $hashRepository;

    public function __construct() {
        $this->hashRepository = new DailyHashRepository();
    }

    /**
     * Gets latest hash from DB
     * @return string
     */
    public function getLatest(): string {
        return $this->hashRepository->getLatest();
    }
}
