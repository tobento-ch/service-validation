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

use Closure;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionUnionType;
use InvalidArgumentException;

/**
 * Passes
 */
class Passes extends Rule implements AutowireAware, ValidationAware, ValidatorAware
{
    use IsEmpty;
    use HasAutowire;
    use HasValidation;
    use HasValidator;
    
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => 'The :attribute is invalid.',
    ];
    
    /**
     * Create a new Passes.
     *
     * @param bool|callable $passes
     * @param null|bool|callable $skipValidation
     * @param null|string $errorMessage
     * @param bool $verifyDeclaredType 
     *    If false, the delcared type of the value closure parameter must be mixed!
     */
    public function __construct(
        protected $passes,
        protected $skipValidation = null,
        protected null|string $errorMessage = null,
        protected bool $verifyDeclaredType = true,
    ) {
        if (!is_bool($passes) && !is_callable($passes)) {
            throw new InvalidArgumentException('passes parameter must be of type bool or callable');
        }
        
        if (
            !is_null($skipValidation)
            && !is_bool($skipValidation)
            && !is_callable($skipValidation)
        ) {
            throw new InvalidArgumentException('skipValidation parameter must be of type bool, callable or null');
        }
    }
    
    /**
     * Create a new instance.
     *
     * @param bool|callable $passes
     * @param null|bool|callable $skipValidation
     * @param null|string $errorMessage
     * @return static
     */
    public static function new(
        $passes,
        $skipValidation = null,
        null|string $errorMessage = null,
    ): static {
        return new static($passes, $skipValidation, $errorMessage);
    }
    
    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool
    {
        if (is_null($this->skipValidation)) {
            return $this->isEmpty($value);
        }
        
        if (is_bool($this->skipValidation)) {
            return $this->skipValidation;
        }
        
        if ($this->autowire()) {
            $skip = $this->autowire()->call($this->skipValidation, ['value' => $value]);
        } else {
            $skip = call_user_func_array($this->skipValidation, [$value]);
        }
        
        if (!is_bool($skip)) {
            return $this->isEmpty($value);
        }
        
        return $skip;
    }
    
    /**
     * Determine if the validation rule passes.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        if (is_bool($this->passes)) {
            return $this->passes;
        }

        $params = [
            'value' => $value,
            'parameters' => $parameters,
            'validator' => $this->validator(),
            'validation' => $this->validation(),
        ];
        
        if (
            $this->verifyDeclaredType
            && $this->passes instanceof Closure
            && ! $this->isValueMatchingDeclaredParameterType($this->passes, $value)
        ) {
            return false;
        }
        
        if ($this->autowire()) {
            $passes = $this->autowire()->call($this->passes, $params);
        } else {
            $passes = call_user_func_array($this->passes, array_values($params));
        }
        
        if (!is_bool($passes)) {
            return false;
        }
        
        return $passes;
    }
    
    /**
     * Returns the validation error messages.
     * 
     * @return array
     */
    public function messages(): array
    {
        if ($this->errorMessage) {
            return ['passes' => $this->errorMessage];
        }
        
        return static::MESSAGES;
    }
    
    /**
     * Returns the validation error messages.
     * 
     * @param Closure $validation
     * @param mixed $value
     * @return bool
     */
    protected function isValueMatchingDeclaredParameterType(Closure $validation, mixed $value): bool
    {
        $refFunction = new ReflectionFunction($validation);

        foreach($refFunction->getParameters() as $parameter) {
            if ($parameter->getName() !== 'value') {
                continue;
            }
            
            $type = $parameter->getType();
            
            if ($parameter->allowsNull() && is_null($value)) {
                return true;
            }
            
            if ($type instanceof ReflectionNamedType) {
                return $this->isTypeMatching($type->getName(), $value);
            }
            
            if ($type instanceof ReflectionUnionType) {
                foreach($type->getTypes() as $namedType) {
                    if ($this->isTypeMatching($namedType->getName(), $value)) {
                        return true;
                    }
                }
            }
            
            return false;
        }
    
        return true;
    }
    
    /**
     * Returns the validation error messages.
     * 
     * @param Closure $validation
     * @param mixed $value
     * @return bool
     */
    protected function isTypeMatching(string $declaredType, mixed $value): bool
    {
        if ($declaredType === 'mixed') {
            return true;
        }
        
        $valueType = gettype($value);
        $numberTypes = ['int', 'float'];
        
        return match ($valueType) {
            'boolean' => $declaredType === 'bool',
            'integer' => in_array($declaredType, $numberTypes),
            'double' => in_array($declaredType, $numberTypes),
            default => $declaredType === $valueType,
        };
    }
}