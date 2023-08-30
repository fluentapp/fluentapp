<?php

namespace App\Domain\Event\Service;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use foroco\BrowserDetection;
use App\Domain\Event\Repository\EventRepository;
use App\Domain\Event\Validator\FqdnValidation;
use App\Domain\Event\Validator\EventTypeValidator;
use App\Domain\DailyHash\Service\DailyHashFinder;
use App\Domain\GeoIP\Service\GeoIPFinder;

class EventCreator {

    private EventRepository $eventRepository;
    private DailyHashFinder $dailHashService;
    private BrowserDetection $browserDetect;
    private GeoIPFinder $geoIPFiner;

    /*
     * This variable is used to protect spoofing the even parameter in the API
     */
    protected $allowedEvents = ['pageview'];

    public function __construct(GeoIPFinder $geoIPFiner) {
        $this->eventRepository = new EventRepository();
        $this->dailHashService = new DailyHashFinder();
        $this->browserDetect = new BrowserDetection();
        $this->geoIPFiner = $geoIPFiner;
    }

    /**
     * @param array $data Data received from the JS tracker
     * @param array $requestInfo passing sanitized $_SERVER from the controller
     */
    public function create($data) {


        $data = $this->sanitize($data);

        $data['hash'] = $this->generateVisitHash(
                $data['remote_address'],
                $data['user_agent']
        );

        $data = $this->getMeta($data);

        $data = $this->getGeoData($data);

        $this->validateAdd($data);

        $this->eventRepository->fill($data);

        $this->eventRepository->save();
    }

    private function generateVisitHash(string $ip, string $userAgent): string {
        $dailyHash = $this->dailHashService->getLatest();

        return hash('sha256', $dailyHash . $ip . $userAgent);
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    private function getGeoData(array $data): array {
        $geoData = $this->geoIPFiner->getInfoByIp($data['remote_address']);
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
    private function getMeta($data) {

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
    private function sanitize($data) {

        // remove www. from domain

        if (str_replace('www.', '', $data['domain'])) {
            $data['domain'] = str_replace('www.', '', $data['domain']);
        }

        return $data;
    }

    /**
     * 
     * @param array $data
     * @throws ValidationException
     * @return void 
     */
    private function validateAdd($data) {

        $validator = Validator::make($data, [
                    'domain' => ['string', 'required', new FqdnValidation],
                    'event' => ['string', 'required', new EventTypeValidator],
                    'page' => 'string|required|url',
                    'referrer' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Invalid Parameters');
        }
    }
}
