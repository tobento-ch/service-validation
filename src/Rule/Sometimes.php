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
 * Sometimes: skips all validations if data is not present.
 */
class Sometimes extends Rule implements ValidationAware, SkipValidationsAware
{
    use HasValidation;
    use IsEmpty;

    /**
     * The error messages.
     */
    public const MESSAGES = [];
    
    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool
    {
        return true;
    }
    
    /**
     * Skips validations depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validations, otherwise false.
     */
    public function skipValidations(mixed $value, string $method = 'passes'): bool
    {
        return $this->passes($value);
    }
    
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
        $isPresent = $this->validation()->data()->has((string)$this->validation()->key());
        
        return !$isPresent;
    }
}