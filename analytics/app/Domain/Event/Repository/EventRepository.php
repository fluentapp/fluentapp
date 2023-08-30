<?php

namespace App\Domain\Event\Repository;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Event\Rules\FqdnValidation;

class EventRepository extends Model {

    use HasFactory;

    protected $table = 'events';
    protected $fillable = [
        'domain',
        'event',
        'page',
        'referrer',
        'hash',
        'os',
        'os_version',
        'browser',
        'browser_version',
        'user_agent',
        'city',
        'country',
        'state',
    ];
}
