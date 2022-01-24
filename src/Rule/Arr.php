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
 * Arr
 */
class Arr extends Rule implements ValidatorAware, ValidationAware
{
    use HasValidator;
    use HasValidation;
    
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'in' => 'The :attribute must be one of :parameters.',
        'each' => 'The :attribute items are invalid.',
        'eachIn' => 'The :attribute must be one of :parameters.',
        'eachWith' => 'The :attribute items are invalid.',
        'min' => 'The :attribute must have at least :parameters[0] items.',
        'max' => 'The :attribute must have at most :parameters[0] items.',
        'invalidValue' => 'The :attribute must be of value :parameters.',
    ];
    
    /**
     * Determine if the value is in the list provided.
     * in:foo:bar
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function in(mixed $value, array $parameters = []): bool
    {
        if (empty($parameters)) {
            return false;
        }
        
        return in_array($value, $parameters, true);
    }
    
    /**
     * Determine if each value in the array is within the list of values provided with the same keys.
     * in:foo:bar
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function each(mixed $value, array $parameters = []): bool
    {
        if (!is_array($value)) {
            return false;
        }
        
        if (empty($parameters)) {
            return false;
        }
        
        $params = [];
        
        foreach($parameters as $k => $parameter)
        {
            if (is_string($parameter) && str_contains($parameter, '=>')) {
                [$pkey, $parameter] = explode('=>', $parameter);
                $params[$pkey] = $parameter;
            } else {
                $params[$k] = $parameter;
            }
        }
        
        $isValid = true;
        
        foreach($value as $key => $val)
        {            
            if (!array_key_exists($key, $params)) {                
                return false;
            }
            
            if ($params[$key] !== $val) {
                
                $this->validation()->errors()->add(
                    level: 'error',
                    message: self::MESSAGES['invalidValue'] ?? 'Invalid value.',
                    context: [
                        ':attribute' => $this->validation()->key().'.'.$key,
                        'rule_parameters' => [$params[$key]],
                        'value' => $val,
                    ],
                    key: $this->validation()->key().'.'.$key,  
                );
                
                $isValid = false;
            }
        }
        
        return $isValid;
    }
    
    /**
     * * Determine if each value in the array is within the list of values provided.
     * in:foo:bar
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function eachIn(mixed $value, array $parameters = []): bool
    {
        if (!is_array($value)) {
            return false;
        }
        
        if (empty($parameters)) {
            return false;
        }
        
        $keys = array_keys($parameters);
        $values = array_values($parameters);
        
        foreach($value as $key => $val)
        {
            if (!in_array($key, $keys, true)) {
                return false;
            }
                        
            if (!in_array($val, $values, true)) {                
                return false;
            }
        }

        return true;
    }
    
    /**
     * Determine if the value is an array with the rules passing.
     * each:int/minNum;1:alpha/maxLen;3:2 = key_rules:value_rules:min:max
     * ['key' => 'int', 'value' => 'required|alpha', 'min' => 1, 'max' => 5]
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function eachWith(mixed $value, array $parameters = []): bool
    {
        if (!is_array($value)) {
            return false;
        }
        
        if (empty($parameters)) {
            return false;
        }
        
        if (isset($parameters[0])) {
            $keyRules = $parameters[0] ?? 'int';
            $keyRules = strtr($keyRules, ['/' => '|', ';' => ':']);            
            $valueRules = $parameters[1] ?? $parameters[0] ?? 'int';
            $valueRules = strtr($valueRules, ['/' => '|', ';' => ':']);
        } else {
            $keyRules = $parameters['key'] ?? 'int';
            $valueRules = $parameters['value'] ?? 'int';       
        }
        
        $isValid = true;
        
        foreach($value as $key => $val)
        {
            $validation = $this->validator()->validating(
                value: $key,
                rules: $keyRules
            );
                        
            if (! $validation->isValid()) {
                return false;
            }
            
            $validation = $this->validator()->validating(
                value: $val,
                rules: $valueRules,
                key: $this->validation()->key().'.'.$key,
            );
            
            $this->validation()->errors()->push($validation->errors());
            
            if (! $validation->isValid()) {
                $isValid = false;
            }
        }
        
        return $isValid;
    }
    
    /**
     * Determine if the array has a minimum size.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function min(mixed $value, array $parameters = []): bool
    {
        if (!is_array($value)) {
            return false;
        }
        
        $min = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $min = (int)$parameters[0];
        }
        
        return count($value) >= $min;
    }
    
    /**
     * Determine if the array has a maximum size.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function max(mixed $value, array $parameters = []): bool
    {
        if (!is_array($value)) {
            return false;
        }
        
        $max = 1;
        
        if (isset($parameters[0]) && is_numeric($parameters[0])) {
            $max = (int)$parameters[0];
        }
        
        return count($value) <= $max;
    }    
}