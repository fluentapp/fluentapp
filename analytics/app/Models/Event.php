<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';

    protected const MAPPED_FIELDS = [
        'deviceFields' => ['os' => 'os', 'browsers' => 'browser', 'sizes' => 'device_type'],
        'locationFields' => ['countries' => 'country', 'cities' => 'city', 'regions' => 'state'],
        'pageFields' => ['entry_pages' => 'entry_page', 'exit_pages' => 'exit_page', 'page' => 'page'],
        'sourceFields' => ['sources' => 'referrer'],
    ];


    /**
     * Get the pageviews for the Event.
     */
    public function pageViews(): HasMany
    {
        return $this->hasMany(Pageview::class, 'event_id', 'id');
    }


    // @todo Omar to implement all filters
    public static function appendQuery($event, $filters)
    {
        $event = $event->where('site_id', $filters['site_id']);
        $moreFilters = !empty($filters['filters']) ? json_decode($filters['filters']) : [];
        foreach ($moreFilters as $filter) {
            $field = self::getValueForKey($filter->key ?? '');
            if (!empty($field)) {
                $event = (isset($filter->operator) && $filter->operator == 'is_not')
                    ? $event->whereNotIn($field, $filter->value ?? [])
                    : $event->whereIn($field, $filter->value ?? []);
            }
        }
        return $event;
    }
    // Function to get the value for a given key
    public static function getValueForKey($key)
    {
        foreach (self::MAPPED_FIELDS as $fieldKey => $values) {
            if (array_key_exists($key, $values)) {
                return $values[$key];
            }
        }
        return null;
    }
}
