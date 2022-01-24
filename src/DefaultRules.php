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

use Tobento\Service\Validation\Rule;

/**
 * DefaultRules
 */
class DefaultRules implements RulesInterface
{
    /**
     * @var RuleFactoryInterface
     */
    protected RuleFactoryInterface $ruleFactory;
    
    /**
     * @var array<string, mixed>
     */
    protected array $rules = [];
    
    /**
     * Create a new DefaultRules.
     *
     * @param null|RuleFactoryInterface $ruleFactory
     */
    public function __construct(
        null|RuleFactoryInterface $ruleFactory = null,
    ) {
        $this->ruleFactory = $ruleFactory ?: new RuleFactory();    
        $this->rules = $this->getDefaultRules();
    }  
    
    /**
     * Add a rule.
     *
     * @param string $name
     * @param mixed $rule
     * @return static $this
     */
    public function add(string $name, mixed $rule): static
    {
        $this->rules[$name] = $rule;
        return $this;
    }

    /**
     * Returns the rule based on the specified rule.
     *
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws RuleNotFoundException
     * @throws InvalidRuleException
     */
    public function get(mixed $rule): RuleInterface
    {
        if (!is_string($rule)) {
            return $this->ruleFactory->createRule($rule);
        }
        
        if (!isset($this->rules[$rule])) {
            throw new RuleNotFoundException(
                $rule,
                'Rule ['.$rule.'] not found'
            );
        }
        
        if ($this->rules[$rule] instanceof RuleInterface) {
            return $this->rules[$rule];
        }
        
        return $this->rules[$rule] = $this->ruleFactory->createRule($this->rules[$rule]);
    }
    
    /**
     * Returns the default rules.
     *
     * @return array<string, mixed>
     */
    protected function getDefaultRules(): array
    {
        return [
            'alphabetic' => [Rule\Strings::class, 'alphabetic'],
            
            'alpha' => [Rule\Strings::class, 'alpha'],
            
            'alphabeticNum' => [Rule\Strings::class, 'alphabeticNum'],
            
            'alnum' => [Rule\Strings::class, 'alnum'],
            
            'digit' => [Rule\Number::class, 'digit'],
            
            'decimal' => [Rule\Number::class, 'decimal'],
            
            'minNum' => [Rule\Number::class, 'min'],
            
            'maxNum' => [Rule\Number::class, 'max'],
            
            'minLen' => [Rule\Length::class, 'min'],
            
            'maxLen' => [Rule\Length::class, 'max'],            
            
            'same' => Rule\Same::class,
            
            'in' => [Rule\Arr::class, 'in'],
            
            'each' => [Rule\Arr::class, 'each'],
            
            'eachIn' => [Rule\Arr::class, 'eachIn'],
            
            'eachWith' => [Rule\Arr::class, 'eachWith'],
            
            'minItems' => [Rule\Arr::class, 'min'],
            
            'maxItems' => [Rule\Arr::class, 'max'],
            
            'email' => [Rule\Address::class, 'email'],
            
            'url' => [Rule\Address::class, 'url'],
            
            'date' => [Rule\Dates::class, 'date'],
            
            'dateFormat' => [Rule\Dates::class, 'dateFormat'],
            
            'dateBefore' => [Rule\Dates::class, 'dateBefore'],
            
            'dateAfter' => [Rule\Dates::class, 'dateAfter'],
 
            'string' => [Rule\Type::class, 'string'],
            
            'int' => [Rule\Type::class, 'int'],
            
            'float' => [Rule\Type::class, 'float'],
            
            'numeric' => [Rule\Type::class, 'numeric'],
            
            'bool' => [Rule\Type::class, 'bool'],
            
            'scalar' => [Rule\Type::class, 'scalar'],
            
            'array' => [Rule\Type::class, 'array'],
            
            'json' => [Rule\Type::class, 'json'],

            'notEmpty' => [Rule\Type::class, 'notEmpty'],
            
            'notNull' => [Rule\Type::class, 'notNull'],
            
            'regex' => Rule\Regex::class,
                        
            'required' => [Rule\Required::class, 'ifNotEmpty'],
                        
            'required_ifEqual' => [Rule\Required::class, 'ifEqual'],
            
            'required_ifIn' => [Rule\Required::class, 'ifIn'],
            
            'required_with' => [Rule\Required::class, 'with'],
            
            'required_without' => [Rule\Required::class, 'without'],
        ];
    }    
}