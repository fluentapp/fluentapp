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
     * @param bool $withPageView should be false for events other than page view
     */
    public function insertEvent(array $data, bool $withPageView = true)
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
            'country_code' => $data['country_code'],
            'city' => $data['city'],
            'state' => $data['state'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'referrer' => $data['referrer'],
            'site_id' => $data['site_id'],
            'utm_source' => $data['utm_source'] ?? '',
            'utm_medium' => $data['utm_medium'] ?? '',
            'utm_campaign' => $data['utm_campaign'] ?? '',
            'utm_content' => $data['utm_content'] ?? '',
            'utm_term' => $data['utm_term'] ?? '',
            'referrer_domain' => @$data['referrer_domain'] // dirty, should be handled in the domain
        ])->execute();

        if ($withPageView) {
            $this->insertEventPageView($data['hash'], $data);
        }
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
