<?php

namespace App\Test\TestCase\Action\Event;

use App\Test\Traits\AppTestTrait;
use Cake\Chronos\Chronos;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;
use App\Test\Fixture\DailyHashFixture;
use App\Test\Fixture\UserSitesFixture;
use App\Test\Fixture\UsersFixture;
use App\Test\Fixture\SiteFixture;

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
        $this->insertFixtures([
            DailyHashFixture::class,
            UsersFixture::class,
            SiteFixture::class,
            UserSitesFixture::class
        ]);

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
        $this->assertJsonData(['result' => 'success', 'settings' => [
                'page_not_found_enabled' => null,
                'page_not_found_titles' => null,
                'external_tracking_enabled' => null
            ]], $response);

        // Check logger
        //$this->assertTrue($this->getLogger()->hasInfoThatContains('Event created successfully'));
        // Check database
        $this->assertTableRowCount(1, 'events');

        $expected = [
            'domain' => "moe.com",
            'event' => "pageview",
            'entry_page' => '/tracker/test/sub/page.html',
            'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html'
        ];

        $this->assertTableRow($expected, 'events', 1);
    }

    public function testCreateEventDomainDoesntExist(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/event',
            [
                    'domain' => "ahmad.com",
                    'event' => "pageview",
                    'page' => 'http://localhost:8080/tracker/test/sub/page.html',
                    'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html'
                ]
        );

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonContentType($response);
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


    public function testCreateEventInvalidUtm(): void
    {
        $this->insertFixtures([
            DailyHashFixture::class,
            UsersFixture::class,
            SiteFixture::class,
            UserSitesFixture::class
        ]);
        $request = $this->createJsonRequest(
            'POST',
            '/event',
            [
                    'domain' => "moe.com",
                    'event' => "pageview",
                    'page' => 'http://localhost:8080/tracker/test/sub/page.html',
                    'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html',
                    'utm_source' => 'as+@$sdf&*&*(&()*&&(*'
                ]
        );

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY, $response->getStatusCode());

        $expected = [
            'error' => [
                'message' => 'Please check your input',
                'code' => 422,
                'details' => [
                    [
                        'message' => 'The utm_source is not valid',
                        'field' => 'utm_source'
                    ]
                ]
            ]
        ];


        $this->assertJsonData($expected, $response);

        $this->assertJsonContentType($response);
    }


    public function testCreateEventValidUtm(): void
    {
        $this->insertFixtures([
            DailyHashFixture::class,
            UsersFixture::class,
            SiteFixture::class,
            UserSitesFixture::class
        ]);
        $request = $this->createJsonRequest(
            'POST',
            '/event',
            [
                    'domain' => "moe.com",
                    'event' => "pageview",
                    'page' => 'http://localhost:8080/tracker/test/sub/page.html',
                    'referrer' => 'http://localhost:8080/tracker/test/root-page-2.html',
                    'utm_source' => 'google',
                    'utm_campaign' => 'end-of-year',
                    'utm_term' => 'click_here',
                    'utm_content' => 'good_offer',
                    'utm_medium' => 'email'
                ]
        );

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());

        $this->assertJsonContentType($response);
    }

}
