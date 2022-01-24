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
 * Same
 */
class Same extends Rule implements ValidationAware
{
    use HasValidation;
    
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => 'The :attribute and :parameters[0] must match.',
    ];
    
    /**
     * Determine if the value is the same of the field provided by parameter.
     * same:field, same:user.password
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        if (!isset($parameters[0]) || !is_string($parameters[0])) {
            return false;
        }
        
        $inputValue = $this->validation()->data()->get($parameters[0]);
        
        return $value === $inputValue;
    }
}