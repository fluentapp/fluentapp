<?php

namespace App\Domain\Event\SiteSettings\Repository;

use App\Factory\QueryFactory;
use App\Domain\Event\SiteSettings\Data\SiteSettingsData;
use DomainException;

class SiteSettingsRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function find(int $siteId): SiteSettingsData
    {
        $query = $this->queryFactory->newSelect('site_settings');
        $query->select([
            'id',
            'site_id',
            'page_not_found_enabled',
            'page_not_found_titles',
            'external_tracking_enabled',
            'created_at',
            'updated_at',
        ]);
        $query->andWhere(['site_id' => $siteId]);

        $row = $query->execute()->fetchAll('assoc') ?: [];

        if (!$row) {
            return new SiteSettingsData([]);
        }

        return new SiteSettingsData($row[0]);
    }
}
