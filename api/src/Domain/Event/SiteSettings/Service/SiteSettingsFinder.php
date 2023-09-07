<?php

namespace App\Domain\Event\SiteSettings\Service;

use App\Domain\Event\SiteSettings\Repository\SiteSettingsRepository;
use App\Domain\Event\SiteSettings\Data\SiteSettingsData;

class SiteSettingsFinder
{
    private SiteSettingsRepository $siteRepository;


    public function __construct(
        SiteSettingsRepository $siteRepository,
    ) {
        $this->siteRepository = $siteRepository;
    }

    public function find(string $fqdn): SiteSettingsData
    {
        return $this->siteRepository->find($fqdn);
    }
}
