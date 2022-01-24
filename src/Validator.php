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

use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Collection\Collection;

/**
 * Validator
 */
class Validator implements ValidatorInterface, RulesAware
{
    /**
     * @var RulesInterface
     */
    protected RulesInterface $rules;

    /**
     * Create a new Validator.
     *
     * @param null|RulesInterface $rules
     * @param null|RulesParserInterface $rulesParser
     * @param null|MessagesFactoryInterface $messagesFactory
     */
    public function __construct(
        null|RulesInterface $rules = null,
        protected null|RulesParserInterface $rulesParser = null,
        protected null|MessagesFactoryInterface $messagesFactory = null,
    ) {        
        $this->rules = $rules ?: new DefaultRules();
        $this->rulesParser = $rulesParser ?: new RulesParser();
        $this->messagesFactory = $messagesFactory ?: new MessagesFactory();
    }

    /**
     * Returns the rules.
     *
     * @return RulesInterface
     */
    public function rules(): RulesInterface
    {
        return $this->rules;
    }

    /**
     * Validating a value with the given rules.
     * 
     * @param mixed $value
     * @param string|array $rules
     * @param array|Collection $data
     * @param null|string $key
     * @return ValidationInterface
     *
     * @throws RuleException
     */
    public function validating(
        mixed $value,
        string|array $rules,
        array|Collection $data = [],
        null|string $key = null
    ): ValidationInterface {
        // parse rules.
        $parsedRules = $this->rulesParser->parse($rules);     
        
        // RulesValidation    
        $validations = [];
        
        foreach($parsedRules as $parsedRule)
        {
            if (! $parsedRule instanceof ParsedRule)
            {
                throw new RuleException(sprintf(
                    'Parsed rule needs to be an instanceof %s',
                    ParsedRule::class
                ));
            }
            
            $rule = $this->rules->get($parsedRule->rule());
            
            if ($rule instanceof Rule\ValidatorAware) {
                $rule->setValidator($this);
            }
            
            $validations[] = new Validation(
                rule: $rule,
                value: $value,
                parameters: $parsedRule->parameters(),
                data: $data,
                key: $key,
                messagesFactory: $this->messagesFactory,
            );
        }

        return new Validations($this->messagesFactory, $data, ...$validations);
    }
    
    /**
     * Validate the data given.
     * 
     * @param mixed $data
     * @param array $rules
     * @return ValidationInterface
     *
     * @throws RuleException
     */
    public function validate(mixed $data, array $rules): ValidationInterface
    {
        // ToDo: handle invalid data provided.
        if (!is_array($data)) {
            return new Validations($this->messagesFactory, []);
        }
        
        // use Collection for notation support.
        $data = new Collection($data);
        
        $validations = [];
        
        foreach($rules as $key => $definition)
        {
            $validations[$key] = $this->validating(
                value: $data->get($key),
                rules: $definition,
                data: $data,
                key: $key,
            );
        }
        
        return new KeyedValidations($this->messagesFactory, $data, $validations);
    }
}