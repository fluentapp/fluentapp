<?php

namespace App\Domain\Event\Service;

use App\Domain\Event\Repository\EventRepository;

class EventUpdator
{
    private EventRepository $eventRepository;


    public function __construct(
        EventRepository $eventRepository,
    ) {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param array $data Data received from the JS tracker
     * @param array $requestInfo passing sanitized $_SERVER from the controller
     */
    public function update(string $hash, array $data)
    {
        // set updated at
        $data['updated_at'] = date("Y-m-d H:i:s");
        $this->eventRepository->updateEvent($data['hash'], $data);
    }
}
