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
 * Strings
 */
class Strings extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'alphabetic' => 'The :attribute must only contain letters.',
        'alpha' => 'The :attribute must only contain letters [a-zA-Z]',
        'alphabeticNum' => 'The :attribute must only contain letters and numbers.',
        'alnum' => 'The :attribute must only contain letters [a-zA-Z] and numbers.',
    ];
    
    /**
     * Determine if value contains only alphabetic characters.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function alphabetic(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool) preg_match('/^[\pL\pM]+$/u', $value);
    }
    
    /**
     * Determine if value contains only alpha characters.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function alpha(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool) preg_match('/^[a-zA-Z]+$/u', $value);
    }
    
    /**
     * Determine if value contains only alphabetic characters.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function alphabeticNum(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return (bool) preg_match('/^[\pL\pM\pN]+$/u', (string) $value);
    }
    
    /**
     * Determine if value contains only alpha and numbers characters.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function alnum(mixed $value, array $parameters = []): bool
    {
        return ctype_alnum($value);
    }
}