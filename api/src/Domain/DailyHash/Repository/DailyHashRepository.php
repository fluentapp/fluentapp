<?php

namespace App\Domain\DailyHash\Repository;

use App\Factory\QueryFactory;
use DomainException;

class DailyHashRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function getLatest(): string
    {
        $query = $this->queryFactory->newSelect('daily_hash');
        $query->select(
            [
                    'hash',
                ]
        );
        $query->order(['created_at' => 'DESC']);

        $row = $query->execute()->fetch('assoc');

        return $row['hash'];
    }
}
