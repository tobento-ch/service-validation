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
 * Regex
 */
class Regex extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => 'The :attribute must match the pattern :parameters[0].',
    ];
    
    /**
     * Determine if the value matches the pattern.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        if (!isset($parameters[0]) || !is_string($parameters[0])) {
            return false;
        }
        
        return (bool) preg_match($parameters[0], $value);
    }
}