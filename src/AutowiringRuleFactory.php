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

use Psr\Container\ContainerInterface;
use Tobento\Service\Validation\Rule\AutowireAware;
use Tobento\Service\Autowire\Autowire;
use Tobento\Service\Autowire\AutowireException;
use Throwable;

/**
 * AutowiringRuleFactory
 */
class AutowiringRuleFactory implements RuleFactoryInterface
{
    /**
     * @var Autowire
     */
    private Autowire $autowire;

    /**
     * Create a new AutowiringRuleFactory.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->autowire = new Autowire($container);
    }
    
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
            return $this->handleRule($rule);
        }
        
        // Rule::class
        if (is_string($rule)) {
            return $this->resolve($rule, [], $rule);
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
                    $this->resolve($rule[0], $rule[2] ?? [], $rule),
                    $rule[1]
                );
            }

            // [Rule::class, ['name' => 'value']]
            if (is_string($rule[0]) && is_array($rule[1])) {
                return $this->resolve($rule[0], $rule[1] ?? [], $rule);
            }        

            // [new Rule(), 'method']
            if ($rule[0] instanceof RuleInterface) {
                return new CallableRule($this->handleRule($rule[0]), $rule[1] ?? 'passes');
            }
        } catch (Throwable $e) {
            throw new InvalidRuleException($rule, 'Could not create rule', 0, $e);
        }

        throw new InvalidRuleException($rule);
    }
                
    /**
     * Resolve the given class.
     *
     * @param string $class
     * @param array<int|string, mixed> $parameters
     * @param mixed $rule
     *
     * @throws InvalidRuleException
     *
     * @return RuleInterface
     */
    protected function resolve(string $class, array $parameters, mixed $rule): RuleInterface
    {
        try {
            $obj = $this->autowire->resolve($class, $parameters);
        } catch (AutowireException $e) {
            throw new InvalidRuleException($rule, 'Could not create rule', 0, $e);
        }
        
        if (! $obj instanceof RuleInterface)
        {
            throw new InvalidRuleException(
                $obj::class,
                'Rule needs to be an instance of '.RuleInterface::class
            );
        }
        
        return $this->handleRule($obj);
    }
    
    /**
     * Returns the created rule.
     *
     * @param RuleInterface $rule
     * @return RuleInterface
     */
    protected function handleRule(RuleInterface $rule): RuleInterface
    {
        if ($rule instanceof AutowireAware) {
            $rule->setAutowire($this->autowire);
        }
        
        return $rule;
    }
}