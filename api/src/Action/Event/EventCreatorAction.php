<?php

namespace App\Action\Event;

use App\Domain\Event\Service\EventCreator;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class EventCreatorAction
{
    private JsonRenderer $renderer;
    private EventCreator $eventCreator;

    public function __construct(EventCreator $eventCreator, JsonRenderer $renderer)
    {
        $this->eventCreator = $eventCreator;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Extract the form data from the request body
        $data = (array) $request->getParsedBody();

        $serverVars = $request->getServerParams();

        // Invoke the Domain with inputs and retain the result
        $eventData = $this->eventCreator->create(array_merge(
            $data,
            [
                'user_agent' => $serverVars['HTTP_USER_AGENT'],
                'remote_address' => $serverVars['REMOTE_ADDR']
            ]
        ));

        // Build the HTTP response
        return $this->renderer
                        ->json($response, ['result' => 'success', 'settings' => $eventData])
                        ->withStatus(StatusCodeInterface::STATUS_CREATED);
    }
}
