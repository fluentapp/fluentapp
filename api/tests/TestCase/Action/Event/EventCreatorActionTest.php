<?php

namespace App\Test\TestCase\Action\Event;

use App\Test\Traits\AppTestTrait;
use Cake\Chronos\Chronos;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use App\Test\Fixture\DailyHashFixture;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\Event\EventCreatorAction
 */
class EventCreatorActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;

    public function testCreateEvent(): void
    {
        Chronos::setTestNow('2023-08-02 00:00:00');
        //create a new daily hash from a fixture
        $this->insertFixtures([DailyHashFixture::class]);

        $request = $this->createJsonRequest(
            'POST',
            '/event',
            [
                    'domain' => "moe.com",
                    'event' => "pageview",
                    'page' => 'http://localhost:8080/tracker/test/sub/page.html',
                    'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html'
                ]
        );

        $response = $this->app->handle($request);




        // No logger errors
        //$this->assertSame([], $this->getLoggerErrors());
        //$this->assertTrue($this->getLogger()->hasInfoThatContains('Event created successfully: 1'));

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['result' => 'success'], $response);

        // Check logger
        //$this->assertTrue($this->getLogger()->hasInfoThatContains('Event created successfully'));

        // Check database
        $this->assertTableRowCount(1, 'events');

        $expected = [
            'domain' => "moe.com",
            'event' => "pageview",
            'page' => 'http://localhost:8080/tracker/test/sub/page.html',
            'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html'
        ];

        $this->assertTableRow($expected, 'events', 1);
    }


    public function testCreateEventInvalidDomain(): void
    {
        $this->insertFixtures([DailyHashFixture::class]);
        $request = $this->createJsonRequest(
            'POST',
            '/event',
            [
                'domain' => "sfgdf",
                'event' => "pageview",
                'page' => 'http://localhost:8080/tracker/test/sub/page.html',
                'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html'
            ]
        );

        $response = $this->app->handle($request);


        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        $this->assertJsonContentType($response);
    }

//    public function testCreateEventValidation(): void
//    {
//        $request = $this->createJsonRequest(
//            'POST',
//            '/api/customers',
//            [
//                'number' => '',
//                'name' => '',
//                'street' => '',
//                'city' => '',
//                'country' => '',
//                'postal_code' => '',
//                'email' => 'mail.example.com',
//            ]
//        );
//
//        $response = $this->app->handle($request);
//
//        // Check response
//        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());
//        $this->assertJsonContentType($response);
//
//        $expected = [
//            'error' => [
//                'message' => 'Please check your input',
//                'details' => [
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[number]',
//                    ],
//                    [
//                        'message' => 'This value should be positive.',
//                        'field' => '[number]',
//                    ],
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[name]',
//                    ],
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[street]',
//                    ],
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[postal_code]',
//                    ],
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[city]',
//                    ],
//                    [
//                        'message' => 'This value should not be blank.',
//                        'field' => '[country]',
//                    ],
//                    [
//                        'message' => 'This value should have exactly 2 characters.',
//                        'field' => '[country]',
//                    ],
//                    [
//                        'message' => 'This value is not a valid email address.',
//                        'field' => '[email]',
//                    ],
//                ],
//            ],
//        ];
//
//        $this->assertJsonData($expected, $response);
//    }
}
