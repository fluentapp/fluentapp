<?php

namespace App\Domain\Event\Site\Data;

use Selective\ArrayReader\ArrayReader;

final class SiteData
{
    public ?int $id = null;
    public ?string $fqdn = null;
    public ?bool $active = null;
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
        $this->fqdn = $reader->findString('fqdn');
        $this->active = $reader->findBool('active');
        $this->createdBy = $reader->findInt('created_at');
        $this->createdOn = $reader->findString('updated_at');
    }
}
