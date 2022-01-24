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

use Tobento\Service\Dater\DateFormatter;
use DateTime;

/**
 * Dates
 */
class Dates extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'date' => 'The :attribute is not a valid date.',
        'dateFormat' => 'The :attribute does not match the format :parameters.',
        'dateBefore' => 'The :attribute must be a date before :parameters[0].',
        'dateAfter' => 'The :attribute must be a date after :parameters[0].',
    ];
    
    /**
     * Determine if the value is a valid date.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function date(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        $date = (new DateFormatter())->toDateTime(value: $value, fallback: null);
        
        return is_null($date) ? false : true;
    }
    
    /**
     * Determine if the value matches any of the date formats.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function dateFormat(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }

        foreach ($parameters as $format)
        {
            $date = DateTime::createFromFormat('!'.$format, $value);

            if ($date && $date->format($format) == $value) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Determine if the value (date) is before the given date.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function dateBefore(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        $currentDate = 'now';
        $sameTimeIsPast = false;
            
        if (isset($parameters[0])) {
            $currentDate = $parameters[0];
        }
        
        if (array_key_exists(1, $parameters)) {
            $sameTimeIsPast = (bool)$parameters[1];
        }  

        return (new DateFormatter())->inPast(
            date: $value,
            currentDate: $currentDate,
            sameTimeIsPast: $sameTimeIsPast,
        );
    }
    
    /**
     * Determine if the value (date) is after the given date.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function dateAfter(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        if (!array_key_exists(1, $parameters)) {
            $parameters[1] = true;
        }
        
        return ! $this->dateBefore($value, $parameters);
    }    
}