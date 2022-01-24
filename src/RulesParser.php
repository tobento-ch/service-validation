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

use InvalidArgumentException;

/**
 * RulesParser
 */
class RulesParser implements RulesParserInterface
{
    /**
     * Parses the rules.
     * 
     * @param string|array $rules
     * @return array<int, ParsedRule>
     *
     * @throws RulesParserException
     */
    public function parse(string|array $rules): array
    {
        if (is_string($rules))
        {
            return $this->parseRulesString($rules);
        }
        
        return $this->parseRulesArray($rules);  
    }    

    /**
     * Parses the rules from string to an array.
     * 
     * @param string $rules
     * @param array $parameters
     * @return array<int, ParsedRule>
     */
    protected function parseRulesString(string $rules, array $parameters = []): array
    {
        if (empty($rules)) {
            return [];
        }
        
        $parsed = [];
        
        foreach(explode('|', $rules) as $rule)
        {            
            if (str_contains($rule, ':')) {
                
                $ruleParams = explode(':', $rule);
                $rule = array_shift($ruleParams);                
                $parameters['rule_parameters'] ??= $ruleParams;
                $parsed[] = new ParsedRule($rule, $parameters);
                unset($parameters['rule_parameters']);
            }
            else
            {
                $parsed[] = new ParsedRule($rule, $parameters);
            }        
        }
        
        return $parsed;
    }
    
    /**
     * Parses the rules from an array.
     * 
     * @param array $rules
     * @return array<int, ParsedRule>
     *
     * @throws RulesParserException
     */
    protected function parseRulesArray(array $rules): array
    {
        // supports the following rules definitions:
        /*[
            'alpha|minLen:2', // single or multiple rules
            'limit_length' => 50, // global
            ':attribute' => '', // global
            'error' => 'Login failed', // global
            
            ['minLen:3', ':attribute' => 'Title', 'error' => 'Custom error message', 'limit_length' => 50],
            
            ['minLen', [3], ':attribute' => 'Title', 'error' => 'Custom error message', 'limit_length' => 50],
            
            new CustomRule(),
            
            // using array for lazy rule:
            [[Alpha::class], [3], 'error' => 'Custom error message'],
            
            // lazy rule with unresolvable class params:
            // [[Rule::class, ['name' => 'value']], [3], 'error' => 'Custom error message'],
            
            [[CustomRule::class, 'name' => 'Sam'], [3], ':attribute' => 'Title'],
        ]
        
        Returns parsed rules as:
        // new Rule()
        // Rule::class
        // [new Rule(), 'method']
        // [Rule::class, 'method', ['name' => 'value']]
        */
        
        // if key is a string it is a global paramater.
        $parameters = [];
                
        foreach($rules as $key => $rule)
        {
            if (is_string($key)) {
                $parameters[$key] = $rule;
                unset($rules[$key]);
            }
        }
                
        $parsed = [];

        foreach($rules as $rule)
        {
            if ($rule instanceof RuleInterface) {
                $parsed[] = new ParsedRule($rule, $parameters);
                continue;
            }
            
            // 'alpha|minLen:2'
            if (is_string($rule)) {
                foreach($this->parseRulesString($rule, $parameters) as $parsedRule) {
                    $parsed[] = $parsedRule;
                }
                
                continue;
            }
            
            if (is_array($rule)) {
                
                if (!isset($rule[0])) {
                    throw new RulesParserException('Invalid array rule.');
                }
                
                if (isset($rule[1]) && is_array($rule[1])) {
                    $parameters['rule_parameters'] = $rule[1];
                }
                
                // [new CustomRule(name: value), [3], ...]
                if ($rule[0] instanceof RuleInterface) {
                    
                    $parameters = $this->mergeRuleParameters($rule, $parameters);
                    
                    $parsed[] = new ParsedRule(
                        [$rule[0], 'passes'],
                        $parameters
                    );
                    
                    continue;
                }
                
                // ['minLen:3', [3], ...] or ['minLen', [3], ...]
                if (is_string($rule[0])) {
                    
                    $parameters = $this->mergeRuleParameters($rule, $parameters);
                    
                    $rulesParsed = $this->parseRulesString($rule[0], $parameters);
                    
                    if (isset($rulesParsed[0])) {
                        $parsed[] = $rulesParsed[0];
                    }
                    
                    continue;
                }
                
                // [[CustomRule::class, ['name' => 'value']], [3], ...]
                // [[CustomRule::class, 'min', ['name' => 'value']], [3], ...]
                // [[Length::class, 'min'], [3], ...]
                // [[new Length(), 'min'], [3], ...]
                if (is_array($rule[0])) {
                    
                    if (!isset($rule[0][0])) {
                        throw new RulesParserException('Invalid array rule.');
                    }

                    $ruleClass = $rule[0][0];
                    $ruleClassMethod = 'passes';
                    $ruleClassParams = [];

                    if (isset($rule[0][1]) && is_string($rule[0][1])) {
                        $ruleClassMethod = $rule[0][1];
                    }
                    
                    if (isset($rule[0][1]) && is_array($rule[0][1])) {
                        $ruleClassParams = $rule[0][1];
                    }
                    
                    if (isset($rule[0][2]) && is_array($rule[0][2])) {
                        $ruleClassParams = $rule[0][2];
                    }
                    
                    $parameters = $this->mergeRuleParameters($rule, $parameters);
                    
                    $parsed[] = new ParsedRule(
                        [$ruleClass, $ruleClassMethod, $ruleClassParams],
                        $parameters
                    );
                    
                    continue;
                }            
                
                throw new RulesParserException('Invalid rule.');
            }            
        }
        
        return $parsed; 
    }
    
    /**
     * Merges rule parameters with global parameters.
     * 
     * @param array $rule
     * @param array $parameters The global parameters
     * @return array The merged parameters
     */
    protected function mergeRuleParameters(array $rule, $parameters): array
    {
        unset($rule[0]);
        unset($rule[1]);
        
        return array_merge($parameters, $rule);
    }
}