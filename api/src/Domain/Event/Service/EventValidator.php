<?php

namespace App\Domain\Event\Service;

use App\Support\Validation;
use Cake\Validation\Validator;
use Selective\Validation\Exception\ValidationException;

/**
 * Service.
 */
final class EventValidator
{
    private Validation $validation;

    /**
     * The constructor.
     *
     * @param EventRepository $repository The repository
     * @param Validation $validation The validation
     */
    public function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Validate new event.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateEvent(array $data): void
    {
        $validator = $this->createValidator();
        $validationResult = $this->validation->validate($validator, $data);

        if ($validationResult->fails()) {
            throw new ValidationException('Please check your input', $validationResult);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        $validator = $this->validation->createValidator();

        $validator->add('domain', 'validateFqdn', [
            'rule' => [$this, 'validateFqdn'],
            'message' => 'The domain is not valid'
        ]);

        $validator->add('utm_source', 'validateUtm', [
            'rule' => [$this, 'validateUtm'],
            'message' => 'The utm_source is not valid'
        ]);

        $validator->add('utm_medium', 'validateUtm', [
            'rule' => [$this, 'validateUtm'],
            'message' => 'The utm_medium is not valid'
        ]);

        $validator->add('utm_campaign', 'validateUtm', [
            'rule' => [$this, 'validateUtm'],
            'message' => 'The utm_campaign is not valid'
        ]);

        $validator->add('utm_content', 'validateUtm', [
            'rule' => [$this, 'validateUtm'],
            'message' => 'The utm_content is not valid'
        ]);

        $validator->add('utm_term', 'validateUtm', [
            'rule' => [$this, 'validateUtm'],
            'message' => 'The utm_term is not valid'
        ]);

        return $validator
                ->requirePresence('page')
                ->requirePresence('domain')
                ->requirePresence('event')
                ->allowEmptyString('utm_source')
                ->allowEmptyString('utm_medium')
                ->allowEmptyString('utm_campaign')
                ->allowEmptyString('utm_content')
                ->allowEmptyString('utm_term')
                ->notEmptyString('page', 'Page is required')
                ->notEmptyString('event', 'Event is required')
                ->notEmptyString('domain', 'Domain is required');
    }

    public function validateUtm($value, $context)
    {
        // Define a regex pattern to allow only alphanumeric characters, hyphens, and underscores
        $pattern = '/^[a-zA-Z0-9\-_]+$/';
        return (bool) preg_match($pattern, $value);
    }

    public function validateFqdn($value, $context)
    {
        // Maximum length for a FQDN is 255 characters
        if (strlen($value) > 255) {
            return false;
        }

        // FQDN should not start or end with a hyphen (-)
        if (strpos($value, '-') === 0 || strpos($value, '-', -1) === strlen($value) - 1) {
            return false;
        }

        // Regular expression pattern to validate FQDN
        $pattern = '/^(?=.{1,255}\.)([a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.){1,126}[a-zA-Z]{2,63}$/';
        return (bool) preg_match($pattern, $value);
    }
}
