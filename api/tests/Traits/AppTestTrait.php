<?php

namespace App\Test\Traits;

use App\Factory\ContainerFactory;
use Selective\TestTrait\Traits\ArrayTestTrait;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Selective\TestTrait\Traits\MockTestTrait;
use Slim\App;
use App\Test\Traits\HttpJsonTestTrait;

/**
 * App Test Trait.
 */
trait AppTestTrait
{
    use ArrayTestTrait;
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;
    use LoggerTestTrait;
    use MockTestTrait;

    protected App $app;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $container = ContainerFactory::createInstance();
        $this->app = $container->get(App::class);

        $this->setUpContainer($container);
        $this->setUpLogger();

        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase(__DIR__ . '/../../resources/schema/schema.sql');
        }
    }
}
