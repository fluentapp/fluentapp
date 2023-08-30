<?php

namespace App\Domain\Event\Site\Repository;

use App\Factory\QueryFactory;
use App\Domain\Event\Site\Data\SiteData;
use DomainException;

class SiteRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function find(string $fqdn): SiteData
    {
        $query = $this->queryFactory->newSelect('sites');
        $query->select([
            'id',
            'fqdn',
            'active',
            'created_at',
            'updated_at',
        ]);
        $query->andWhere(['fqdn' => $fqdn]);

        $row = $query->execute()->fetchAll('assoc') ?: [];

        if (!$row) {
            throw new DomainException(sprintf('Site Check Failed: %s', $fqdn));
        }

        return new SiteData($row[0]);
    }
}
