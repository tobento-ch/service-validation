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
 * Number
 */
class Number extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'digit' => 'The :attribute must only contain digits.',
        'decimal' => 'The :attribute must be decimal.',
        'min' => 'The :attribute must be at least :parameters[0].',
        'max' => 'The :attribute must at most :parameters[0].',        
    ];
    
    /**
     * Determine if the value is a digit.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function digit(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return (bool) preg_match('/^[0-9]+$/', (string)$value);
    }
    
    /**
     * Determine if the value is a decimal.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function decimal(mixed $value, array $parameters = []): bool
    {
        // valid: 23.00, '22.50', 0, -0.00000, '-0.1', 50, '55'

        if (is_string($value) && preg_match('/\s/',$value)) {
            return false;
        }
        
        if (is_numeric($value)) {
            return true;
        }
        
        if (!is_string($value)) {
            return false;
        }

        return (bool) preg_match('/^(\-)?[0-9]+(\.)?([0-9])*$/', $value);
    }
    
    /**
     * Determine if value has a minimum number.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function min(mixed $value, array $parameters = []): bool
    {
        $min = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $min = $parameters[0];
        }
        
        if (!is_numeric($value)) {
            return false;
        }

        return $value >= $min;
    }
    
    /**
     * Determine if value has a maximum number.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function max(mixed $value, array $parameters = []): bool
    {
        $max = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $max = $parameters[0];
        }

        if (!is_numeric($value)) {
            return false;
        }
        
        return $value <= $max;
    }    
}