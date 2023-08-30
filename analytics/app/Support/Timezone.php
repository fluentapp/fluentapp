<?php

namespace App\Support;

use DateTime;
use DateTimeZone;

class Timezone
{
    /**
     * Get all timezones with formatted offsets.
     *
     * @return array
     */
    public static function getAllTimezones(): array
    {
        $timezones = timezone_identifiers_list();
        $timezonesFormatted = [];

        foreach ($timezones as $timezone) {

            $timezonesFormatted[] = [
                'timezone' => $timezone,
                'timezoneFormat' => self::formatTimezone($timezone),
            ];
        }

        return $timezonesFormatted;
    }
    /**
     * Get a specific timezone with formatted offset.
     *
     * @param string $timezone
     * @return string
     */
    public static function formatTimezone($timezone): string
    {
        $dateTime = new DateTime('now', new DateTimeZone($timezone));
        $offset = $dateTime->format('P');
        $formattedTimezone = "$timezone (GMT$offset)";
        return $formattedTimezone;
    }
    /**
     * Get a specific timezone offset.
     *
     * @param string $timezone
     * @return string
     */
    public static function getTimezoneOffset($timezone): string
    {
        $dateTime = new DateTime('now', new DateTimeZone($timezone));
        return $dateTime->format('P');
    }
}
