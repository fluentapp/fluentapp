<?php

namespace App\Domain\DailyHash\Service;

use App\Domain\DailyHash\Repository\DailyHashRepository;

class DailyHashFinder
{
    private DailyHashRepository $hashRepository;

    public function __construct(DailyHashRepository $hashRepository)
    {
        $this->hashRepository = $hashRepository;
    }

    /**
     * Gets latest hash from DB
     * @return string
     */
    public function getLatest(): string
    {
        return $this->hashRepository->getLatest();
    }
}
