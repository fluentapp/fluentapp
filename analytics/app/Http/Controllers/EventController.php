<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Domain\Event\Service\EventCreator;

class EventController extends Controller {

    private $eventService;

    public function __construct(EventCreator $eventCreator) {
        $this->eventService = $eventCreator;
    }

    public function event(Request $request) {

        // the below should be a middle in the future
        try {
            $this->eventService->create(
                    array_merge($request->all(),
                            [
                                'user_agent' => $request->server('HTTP_USER_AGENT'),
                                'remote_address' => $request->server('REMOTE_ADDR')
                            ])
            );
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }


        return response()->json(['message' => 'Data saved successfully'], 201);
    }
}
