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
 * Required
 */
class Required extends Rule implements ValidationAware
{
    use HasValidation;
    use IsEmpty;

    /**
     * The error messages.
     */
    public const MESSAGES = [
        'ifNotEmpty' => 'The :attribute is required.',
        'ifIn' => 'The :attribute is required when :parameters[0] is one of :parameters[-1].',
        'without' => 'The :attribute is required when :parameters is not present.',
        'with' => 'The :attribute is required when :parameters is present.',
        'ifEqual' => 'The :attribute is required when :parameters[0] is :parameters[1].',
    ];
    
    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool
    {
        return ! $this->isEmpty($value);
    }

    /**
     * Required if the value is not empty.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function ifNotEmpty(mixed $value, array $parameters = []): bool
    {
        return !empty($value);
    }
    
    /**
     * Required if another fields value is in the list provided.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function ifIn(mixed $value, array $parameters = []): bool
    {
        if (!isset($parameters[0]) || !is_string($parameters[0])) {
            return true;
        }
        
        $value = $this->validation()->data()->get($parameters[0]);

        unset($parameters[0]);
        
        return !in_array($value, $parameters);
    }
    
    /**
     * Required if one of the fields are not present or empty.
     *
     * @example required_without:field:another_field
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function without(mixed $value, array $parameters = []): bool
    {        
        foreach($parameters as $parameter)
        {
            if (!is_string($parameter)) {
                return false;
            }

            if ($this->isEmpty($this->validation()->data()->get($parameter))) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Required if one of the fields are present and not empty.
     *
     * @example required_without:field:another_field
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function with(mixed $value, array $parameters = []): bool
    {        
        foreach($parameters as $parameter)
        {
            if (!is_string($parameter)) {
                return false;
            }

            if (! $this->isEmpty($this->validation()->data()->get($parameter))) {
                return false;
            }
        }
        
        return true;
    }    
    
    /**
     * Required when field is equal to value.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function ifEqual(mixed $value, array $parameters = []): bool
    {        
        if (!isset($parameters[0]) || !is_string($parameters[0])) {
            return false;
        }
        
        if (!array_key_exists(1, $parameters)) {
            return false;
        }

        if (! $this->validation()->data()->has($parameters[0])) {
            return true;
        }
        
        $keyValue = $this->validation()->data()->get($parameters[0]);
        
        return $keyValue !== $parameters[1];
    }
}