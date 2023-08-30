<?php

namespace App\Domain\DailyHash\Service;

use Illuminate\Support\Facades\Log;
use App\Domain\DailyHash\Repository\DailyHashRepository;

class DailyHashCreator {

    private DailyHashRepository $hashRepository;

    public function __construct() {
        $this->hashRepository = new DailyHashRepository();
    }

    /**
     * Generates a random hash/salt
     * @return string
     */
    public function getLatest(): string {
        return $this->hashRepository->getLatest();
    }

    /**
     * Rotates the hash
     * @return string the generated hash
     */
    public function rotate() {
        return $this->hashRepository->rotate();
    }
}
