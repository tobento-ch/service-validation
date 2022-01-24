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

namespace Tobento\Service\Validation;

/**
 * CallableRule
 */
class CallableRule implements RuleInterface
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'The :attribute is invalid.',
    ];
    
    /**
     * Create a new CallableRule.
     *
     * @param RuleInterface $rule
     * @param string $method The rule method to call.
     */
    public function __construct(
        protected RuleInterface $rule,
        protected string $method,
    ) {}

    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool
    {
        return $this->rule->skipValidation($value, $this->method);
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
        return call_user_func([$this->rule, $this->method], $value, $parameters);
    }
    
    /**
     * Returns the validation error messages.
     * 
     * @return array
     */
    public function messages(): array
    {        
        $messages = $this->rule->messages()[$this->method] ?? static::MESSAGES;
            
        return is_array($messages) ? $messages : [$messages];
    }
    
    /**
     * Returns the rule.
     * 
     * @return RuleInterface
     */
    public function rule(): RuleInterface
    {
        return $this->rule;
    }
    
    /**
     * Returns the method.
     * 
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }
}