<?php

namespace App\Domain\Event\Repository;

use App\Factory\QueryFactory;
use DomainException;

class EventRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     *
     * @param array $data
     */
    public function insertEvent(array $data)
    {
        $this->queryFactory->newInsert('events', [
            'hash' => $data['hash'],
            'event' => $data['event'],
            'entry_page' => $data['page'],
            'domain' => $data['domain'],
            'user_agent' => $data['user_agent'],
            'os' => $data['os'],
            'os_version' => $data['os_version'],
            'device_type' => $data['device_type'],
            'browser' => $data['browser'],
            'browser_version' => $data['browser_version'],
            'country' => $data['country'],
            'city' => $data['city'],
            'state' => $data['state'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'referrer' => $data['referrer'],
            'site_id' => $data['site_id'],
            'referrer_domain' => @$data['referrer_domain'] // dirty, should be handled in the domain
        ])->execute();

        $this->insertEventPageView($data['hash'], $data);
    }

    public function updateEvent(string $hash, array $data)
    {
        $event = $this->getEvent($hash);

        $this->queryFactory->newUpdate('events', [
            'is_bounced' => $data['is_bounced'],
            'duration' => $data['duration'],
            'exit_page' => $data['exit_page'],
            'updated_at' => $data['updated_at']
        ])->where(['id' => $event['id']])->execute();
    }

    /**
     *
     * @param string $hash
     * @param array $data
     * @throws Exception when the hash is not found
     */
    public function insertEventPageView(string $hash, array $data)
    {
        $event = $this->getEvent($hash);

        $this->queryFactory->newInsert('pageviews', [
            'event_id' => $event['id'],
            'page' => $data['page'],
            'created_at' => $data['created_at']
        ])->execute();
    }

    /**
     *
     * @param string $hash
     * @return array
     * @throws Exception when hash is not found
     */
    public function getEvent(string $hash): array
    {
        $query = $this->queryFactory->newSelect('events');
        $query->select('*');
        $query->where([
            'hash' => $hash
        ]);
        $query->orderDesc('created_at');

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new Exception('Event Record not found');
        }

        return $row;
    }

    /**
     * Checks if a event(session) with the sent hash exists
     * @param string $hash
     * @return bool
     */
    public function eventExists(string $hash): bool
    {
        $query = $this->queryFactory->newSelect('events');
        $query->select('*');
        $query->where([
            'hash' => $hash
        ]);

        return (bool) $query->execute()->fetch('assoc');
    }
}
