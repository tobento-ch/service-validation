<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Validation\Rule;

/**
 * Address
 */
class Address extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'email' => 'The :attribute must be a valid email address.',
        'url' => 'The :attribute must be a valid URL.',
    ];
    
    /**
     * Determine if the value is a valid email.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function email(mixed $value, array $parameters = []): bool
    {
        $valid = (bool)filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
        
        if ($valid) {
            return true;
        }
        
        if (!is_string($value)) {
            return false;
        }        
        
        $v = explode('@', $value);
        $v[1] = idn_to_ascii($v[1]);

        return (bool)filter_var(implode('@', $v), FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE);
    }
    
    /**
     * Determine if the value is a valid url.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function url(mixed $value, array $parameters = []): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_URL);
    }    
}