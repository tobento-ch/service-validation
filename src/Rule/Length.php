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
 * Length
 */
class Length extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'min' => 'The :attribute must at least contain :parameters[0] chars.',
        'max' => 'The :attribute must at most contain :parameters[0] chars.',
    ];
    
    /**
     * Determine if value has a minimum length.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function min(mixed $value, array $parameters = []): bool
    {
        $min = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $min = (int)$parameters[0];
        }

        if (is_string($value)) {
            return mb_strlen($value) >= $min;
        }
        
        if (is_numeric($value)) {
            return mb_strlen((string)$value) >= $min;
        }        
                
        return false;
    }
    
    /**
     * Determine if value has a maximum length.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function max(mixed $value, array $parameters = []): bool
    {
        $max = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $max = (int)$parameters[0];
        }

        if (is_string($value)) {
            return mb_strlen($value) <= $max;
        }
        
        if (is_numeric($value)) {
            return mb_strlen((string)$value) <= $max;
        }        
                
        return false;
    }
}