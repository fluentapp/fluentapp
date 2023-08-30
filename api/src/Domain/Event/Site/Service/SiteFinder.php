<?php

namespace App\Domain\Event\Site\Service;

use App\Domain\Event\Site\Repository\SiteRepository;
use App\Domain\Event\Site\Data\SiteData;

class SiteFinder
{
    private SiteRepository $siteRepository;


    public function __construct(
        SiteRepository $siteRepository,
    ) {
        $this->siteRepository = $siteRepository;
    }

    public function find(string $fqdn): SiteData
    {
        return $this->siteRepository->find($fqdn);
    }
}
