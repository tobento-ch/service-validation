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
use Tobento\Service\Validation\Rule\ValidationAware;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Collection\Collection;

/**
 * Validation
 */
final class Validation implements ValidationInterface
{
    /**
     * @var Collection
     */
    private Collection $data;
    
    /**
     * @var Collection The valid data.
     */
    private Collection $valid;
    
    /**
     * @var Collection The invalid data.
     */
    private Collection $invalid;    
    
    /**
     * @var bool
     */
    private bool $isValid = false;
    
    /**
     * @var MessagesInterface
     */
    private MessagesInterface $errors;
    
    /**
     * Create a new Validation.
     *
     * @param RuleInterface $rule
     * @param mixed $value
     * @param array $parameters
     * @param array|Collection $data
     * @param null|string $key
     * @param null|MessagesFactoryInterface $messagesFactory
     */
    public function __construct(
        private RuleInterface $rule,
        private mixed $value,
        private array $parameters = [],
        array|Collection $data = [],
        private null|string $key = null,
        null|MessagesFactoryInterface $messagesFactory = null,
    ) {
        $messagesFactory = $messagesFactory ?: new MessagesFactory();
        $this->errors = $messagesFactory->createMessages();            
        $this->data = is_array($data) ? new Collection($data) : $data;        
        $this->validate($rule, $value, $parameters);
        
        if (is_null($key)) {
            $this->valid = new Collection();
            $this->invalid = new Collection();
            return;
        }
        
        if ($this->isValid()) {
            $this->valid = new Collection($this->data->only([$key]));
            $this->invalid = new Collection();            
        } else {
            $this->valid = new Collection();
            $this->invalid = new Collection($this->data->only([$key]));
        }
    }

    /**
     * Returns true if the validation is valid, otherwise false.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }
    
    /**
     * Returns the errors.
     *
     * @return MessagesInterface
     */
    public function errors(): MessagesInterface
    {
        return $this->errors;
    }
    
    /**
     * Returns the data.
     *
     * @return Collection
     */
    public function data(): Collection
    {
        return $this->data;
    }
    
    /**
     * Returns the valid data.
     *
     * @return Collection
     */
    public function valid(): Collection
    {
        return $this->valid;
    }
    
    /**
     * Returns the invalid data.
     *
     * @return Collection
     */
    public function invalid(): Collection
    {
        return $this->invalid;
    }
    
    /**
     * Returns the rule.
     *
     * @return null|RuleInterface
     */
    public function rule(): null|RuleInterface
    {
        return $this->rule;
    }
    
    /**
     * Returns the value.
     *
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /**
     * Returns the parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
    
    /**
     * Returns the key if any.
     *
     * @return null|string
     */
    public function key(): null|string
    {
        return $this->key;
    }
    
    /**
     * Validates.
     *
     * @param RuleInterface $rule
     * @param mixed $value
     * @param array $parameters
     * @return void
     *
     * @psalm-suppress UndefinedInterfaceMethod
     */
    private function validate(RuleInterface $rule, mixed $value, array $parameters): void
    {
        if ($rule->skipValidation($value, 'passes')) {
            $this->isValid = true;
            return;
        }
        
        // we only pass the rule parameters to the rule.        
        $ruleParamaters = $parameters['rule_parameters'] ?? [];
        
        if (!is_array($ruleParamaters)) {
            $ruleParamaters = [];
        }
        
        if ($rule instanceof ValidationAware) {
            $rule->setValidation($this);
        }
        
        if ($rule instanceof CallableRule && $rule->rule() instanceof ValidationAware) {
            $rule->rule()->setValidation($this);
        }        
        
        $this->isValid = $rule->passes($value, $ruleParamaters);
        
        if ($this->isValid) {
            return;
        }
                
        // if we have a custom error message use it.
        if (isset($parameters['error']) && is_string($parameters['error'])) {
            
            $parameters['from'] = $this::class;
            $parameters['rule'] = $rule::class;
            $parameters['value'] = $value;
            
            $this->errors()->add(
                level: 'error',
                message: $parameters['error'],
                context: $parameters,
                key: $this->key(),
            );
            
            return;
        }        
        
        foreach($rule->messages() as $message)
        {
            $parameters['from'] = $this::class;
            $parameters['rule'] = $rule::class;
            
            if ($rule instanceof CallableRule) {
                $parameters['rule'] = $rule->rule()::class.'::'.$rule->method();
            }
            
            $parameters['value'] = $value;
            
            $this->errors()->add(
                level: 'error',
                message: $message,
                context: $parameters,
                key: $this->key(),
            );         
        }
    }
}