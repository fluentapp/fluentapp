<?php

namespace App\Domain\Event\Service;

use foroco\BrowserDetection;
use App\Domain\Event\Service\EventValidator;
use App\Domain\Event\Repository\EventRepository;
use App\Domain\DailyHash\Service\DailyHashFinder;
use App\Domain\GeoIP\Service\GeoIPFinder;
use App\Domain\Event\Site\Service\SiteFinder;
use App\Support\ReferrerExtractor;
use App\Domain\Event\Site\Data\SiteData;
use App\Domain\Event\SiteSettings\Service\SiteSettingsFinder;

class EventCreator
{
    private EventRepository $eventRepository;
    private EventValidator $eventValidator;
    private DailyHashFinder $dailHashService;
    private BrowserDetection $browserDetect;
    private GeoIPFinder $geoIPFinder;
    private ReferrerExtractor $referrerExtractor;
    private EventUpdator $eventUpdator;
    private SiteFinder $siteFinder;
    private SiteSettingsFinder $siteSettingsFinder;
    /*
     * This variable is used to protect spoofing the even parameter in the API
     */
    protected $allowedEvents = ['pageview'];

    public function __construct(
        EventRepository $eventRepository,
        DailyHashFinder $dailHashService,
        BrowserDetection $browserDetect,
        EventValidator $eventValidator,
        GeoIPFinder $geoIPFinder,
        ReferrerExtractor $referrerExtractor,
        EventUpdator $eventUpdator,
        SiteFinder $siteFinder,
        SiteSettingsFinder $siteSettingsFinder
    ) {
        $this->eventRepository = $eventRepository;
        $this->dailHashService = $dailHashService;
        $this->browserDetect = $browserDetect;
        $this->eventValidator = $eventValidator;
        $this->geoIPFinder = $geoIPFinder;
        $this->referrerExtractor = $referrerExtractor;
        $this->eventUpdator = $eventUpdator;
        $this->siteFinder = $siteFinder;
        $this->siteSettingsFinder = $siteSettingsFinder;
    }

    /**
     * @param array $data Data received from the JS tracker
     * @param array $requestInfo passing sanitized $_SERVER from the controller
     */
    public function create($data)
    {
        $this->eventValidator->validateEvent($data);
        $data = $this->sanitize($data);

        // You Shall Not Pass!
        $siteData = $this->siteFinder->find($data['domain']);
        $data['site_id'] = $siteData->id;

        // Get Site Settings
        $siteSettings = $this->siteSettingsFinder->find($siteData->id);

        $data['created_at'] = date("Y-m-d H:i:s");
        $data['updated_at'] = date("Y-m-d H:i:s");

        $data['hash'] = $this->generateVisitHash(
            $data['remote_address'],
            $data['user_agent'],
            $data['domain'],
        );

        $data = $this->getMeta($data);

        $data = $this->getGeoData($data);

        if (isset($data['referrer'])) {
            $referrerDomain = $this->referrerExtractor->extract($data['referrer']);
            if ($referrerDomain != $data['domain']) {
                $data['referrer_domain'] = $referrerDomain;
            }
        }


        /**
         * This seems to be a stinky code, conider refactoring or splitting
         * The 404 & Custom events into separate Domain
         */

        if ($data['event'] == 'not_found') {
            $this->eventRepository->insertEvent($data, false);
            return [
                'page_not_found_enabled' => $siteSettings->pageNotFoundEnabled,
                'page_not_found_titles' => $siteSettings->pageNotFoundTitles,
                'external_tracking_enabled' => $siteSettings->externalTrackingEnabled
            ];
        }

        /**
         * Returning visitor update:
         * 1.Check if visit time is more than 30 minutes
         * 2.Update bounce flag
         * 3.Calculate the visit time
         * 4.Insert into the pageviews table
         */
        if ($this->eventRepository->eventExists($data['hash'])) {
            $event = $this->eventRepository->getEvent($data['hash']);

            $now = new \DateTime('now');
            $eventDate = new \DateTime($event['created_at']);

            $timeDifference = $now->diff($eventDate);

            $minutesDifference = $timeDifference->days * 24 * 60 +
                    $timeDifference->h * 60 +
                    $timeDifference->i;
            $secondsDifference = $timeDifference->days * 24 * 60 * 60 +
                    $timeDifference->h * 60 * 60 +
                    $timeDifference->i * 60 +
                    $timeDifference->s;

            if ($minutesDifference >= 30) {
                $this->eventRepository->insertEvent($data);
                return [
                    'page_not_found_enabled' => $siteSettings->pageNotFoundEnabled,
                    'page_not_found_titles' => $siteSettings->pageNotFoundTitles,
                    'external_tracking_enabled' => $siteSettings->externalTrackingEnabled
                ];
            }
            $this->eventRepository->insertEventPageView($data['hash'], $data);

            $data['is_bounced'] = 0;
            $data['duration'] = (float) $secondsDifference;
            $data['exit_page'] = $data['page'];

            $this->eventUpdator->update($data['hash'], $data);
        } else {
            $this->eventRepository->insertEvent($data);
        }

        return [
            'page_not_found_enabled' => $siteSettings->pageNotFoundEnabled,
            'page_not_found_titles' => $siteSettings->pageNotFoundTitles,
            'external_tracking_enabled' => $siteSettings->externalTrackingEnabled
        ];
    }

    private function generateVisitHash(string $ip, string $userAgent, string $domain): string
    {
        $dailyHash = $this->dailHashService->getLatest();

        return hash('sha256', $dailyHash . $domain . $ip . $userAgent);
    }

    /**
     *
     * @param array $data
     * @return array
     */
    private function getGeoData(array $data): array
    {
        $geoData = $this->geoIPFinder->getInfoByIp($data['remote_address']);
        $data['country'] = $geoData->country;
        $data['city'] = $geoData->city;
        $data['state'] = $geoData->state;
        return $data;
    }

    /**
     * This function should set the OS, Browser, Browser version, etc..
     * More validation should be added later on..
     * @param array $data
     */
    private function getMeta($data)
    {
        $browserData = $this->browserDetect->getAll($data['user_agent']);
        $data['os'] = $browserData['os_name'];
        $data['os_version'] = $browserData['os_version'];
        $data['device_type'] = $browserData['device_type'];
        $data['browser'] = $browserData['browser_name'];
        $data['browser_version'] = $browserData['browser_version'];
        return $data;
    }

    /**
     * Sanitize user input
     * @param array $data
     * @return array
     */
    private function sanitize($data)
    {
        $parsedUrl = parse_url($data['page']);

        // remove www. from domain
        if (str_replace('www.', '', $data['domain'])) {
            $data['domain'] = str_replace('www.', '', $data['domain']);
        }

        $data['page'] = $parsedUrl['path'];
        return $data;
    }
}
