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

use Throwable;

/**
 * RuleFactory
 */
class RuleFactory implements RuleFactoryInterface
{
    /**
     * Returns the created rule.
     *
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws InvalidRuleException
     */
    public function createRule(mixed $rule): RuleInterface
    {
        // supports the following definition:
        // new Rule()
        // Rule::class
        // [new Rule(), 'method']
        // [Rule::class, 'method', ['name' => 'value']]
        // [Rule::class, ['name' => 'value']]
        
        // new Rule()
        if ($rule instanceof RuleInterface) {
            return $rule;
        }
        
        // Rule::class
        if (is_string($rule)) {
            return $this->createRuleFromString($rule);
        }
        
        if (!is_array($rule)) {
            throw new InvalidRuleException($rule);
        }
        
        if (!isset($rule[0])) {
            throw new InvalidRuleException($rule);
        }
        
        try {
            // [Rule::class, 'method', ['name' => 'value']]
            if (is_string($rule[0]) && is_string($rule[1])) {
                return new CallableRule(
                    $this->createRuleFromString($rule[0], $rule[2] ?? []),
                    $rule[1]
                );
            }

            // [Rule::class, ['name' => 'value']]
            if (is_string($rule[0]) && is_array($rule[1])) {
                return $this->createRuleFromString($rule[0], $rule[1] ?? []);
            }        

            // [new Rule(), 'method']
            if ($rule[0] instanceof RuleInterface) {
                return new CallableRule($rule[0], $rule[1] ?? 'passes');
            }
        } catch (Throwable $e) {
            throw new InvalidRuleException($rule, 'Could not create rule', 0, $e);
        }

        throw new InvalidRuleException($rule);
    }
    
    /**
     * Returns the created rule.
     *
     * @param string $rule
     * @param mixed $parameters
     * @return RuleInterface
     *
     * @throws InvalidRuleException
     */
    protected function createRuleFromString(string $rule, mixed $parameters = []): RuleInterface
    {
        try {
            
            if (is_array($parameters)) {
                return new $rule(...$parameters);
            }
            
            return new $rule();
            
        } catch (Throwable $e) {
            throw new InvalidRuleException($rule, 'Could not create rule', 0, $e);
        }
    }
}