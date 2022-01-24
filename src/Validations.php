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

use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Collection\Collection;

/**
 * Validations
 */
final class Validations implements ValidationInterface
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
     * Create a new Validations.
     *
     * @param MessagesFactoryInterface $messagesFactory
     * @param array|Collection $data
     * @param ValidationInterface ...$validation
     */
    public function __construct(
        MessagesFactoryInterface $messagesFactory,
        array|Collection $data,
        ValidationInterface ...$validation,
    ) {
        $this->errors = $messagesFactory->createMessages();
        $this->data = is_array($data) ? new Collection($data) : $data;
        $this->valid = new Collection();
        $this->invalid = new Collection();
        $this->validate($validation);        
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
        return null;
    }
    
    /**
     * Returns the key if any.
     *
     * @return null|string
     */
    public function key(): null|string
    {
        return null;
    }

    /**
     * Validates.
     *
     * @param array<int, ValidationInterface> $validations
     * @return void
     */
    private function validate(array $validations): void
    {        
        $this->isValid = true;
        
        foreach($validations as $validation)
        {            
            if (! $validation->isValid()) {
                $this->isValid = false;
            }
            
            $this->errors->push($validation->errors());
            
            if (is_null($validation->key())) {
                continue;
            }
            
            if ($validation->isValid()) {
                $this->valid->set($validation->key(), $this->data()->get($validation->key()));
            } else {
                $this->invalid->set($validation->key(), $this->data()->get($validation->key()));
            }
        }
    }
}