<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');
    $app->post('/event', \App\Action\Event\EventCreatorAction::class);
    $app->options('/[{path:.*}]', \App\Action\PreflightAction::class);
};
