<?php

namespace App\Domain\Event\SiteSettings\Data;

use Selective\ArrayReader\ArrayReader;

final class SiteSettingsData
{
    public ?int $id = null;
    public ?int $siteId = null;
    public ?bool $pageNotFoundEnabled = null;
    public ?string $pageNotFoundTitles = null;
    public ?bool $externalTrackingEnabled = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;


    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('id');
        $this->siteId = $reader->findInt('site_id');
        $this->pageNotFoundEnabled = $reader->findBool('page_not_found_enabled');
        $this->pageNotFoundTitles = $reader->findString('page_not_found_titles');
        $this->externalTrackingEnabled = $reader->findBool('external_tracking_enabled');
        $this->createdAt = $reader->findString('created_at');
        $this->updatedAt = $reader->findString('updated_at');
    }
}
