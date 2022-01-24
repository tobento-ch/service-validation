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

use JsonException;

/**
 * Type
 */
class Type extends Rule
{
    use IsEmpty;
    
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'string' => 'The :attribute must be a string.',
        'int' => 'The :attribute must be an int.',
        'float' => 'The :attribute must be a float.',
        'numeric' => 'The :attribute must be numeric.',
        'bool' => 'The :attribute must be a boolean.',
        'scalar' => 'The :attribute must be scalar.',
        'array' => 'The :attribute must be an array.',
        'json' => 'The :attribute must be a valid JSON string.',
        'notEmpty' => 'The :attribute cannot be empty.',
        'notNull' => 'The :attribute cannot be null.',
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
        if (in_array($method, ['notEmpty', 'notNull'])) {
            return false;    
        }
        
        return $this->isEmpty($value);
    }

    /**
     * Determine if the value is of type string.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function string(mixed $value, array $parameters = []): bool
    {
        return is_string($value);
    }
    
    /**
     * Determine if the value is of type int.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function int(mixed $value, array $parameters = []): bool
    {
        return is_int($value);
    }
    
    /**
     * Determine if the value is of type float.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function float(mixed $value, array $parameters = []): bool
    {
        return is_float($value);
    }
    
    /**
     * Determine if the value is of type numeric.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function numeric(mixed $value, array $parameters = []): bool
    {
        return is_numeric($value);
    }    
    
    /**
     * Determine if the value is of type scalar.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function scalar(mixed $value, array $parameters = []): bool
    {
        return is_scalar($value);
    }
    
    /**
     * Determine if the value is of type bool.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function bool(mixed $value, array $parameters = []): bool
    {                
        $isBoolean = [true, false, 0, 1, '0', '1'];
        return in_array($value, $isBoolean, true);
    }

    /**
     * Determine if the value is of type array.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function array(mixed $value, array $parameters = []): bool
    {
        return is_array($value);
    }
    
    /**
     * Determine if the value is a valid JSON string.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     *
     * @psalm-suppress UnusedFunctionCall
     */
    public function json(mixed $value, array $parameters = []): bool
    {
        if (!is_string($value)) {
            return false;
        }
        
        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            return true;
        } catch (JsonException $e) {
            return false;
        }
    }    
        
    /**
     * Determine if the value is not empty.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function notEmpty(mixed $value, array $parameters = []): bool
    {
        return !empty($value);
    }
    
    /**
     * Determine if the value is not null.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function notNull(mixed $value, array $parameters = []): bool
    {
        return !is_null($value);
    }
}