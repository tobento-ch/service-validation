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
 * KeyedValidations
 */
final class KeyedValidations implements ValidationInterface
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
     * @param array<string, Validations> $validations
     */
    public function __construct(
        MessagesFactoryInterface $messagesFactory,
        array|Collection $data,
        array $validations,
    ) {
        $this->errors = $messagesFactory->createMessages();
        $this->data = is_array($data) ? new Collection($data) : $data;
        $this->valid = new Collection();
        $this->invalid = new Collection();
        $this->validate($validations);
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
     * @param array<string, Validations> $validations
     * @return void
     */
    private function validate(array $validations): void
    {
        $this->isValid = true;
        
        foreach($validations as $key => $validation)
        {
            if ($validation->isValid()) {
                $this->valid->set($key, $this->data()->get($key));
            } else {
                $this->isValid = false;
                $this->invalid->set($key, $this->data()->get($key));
            }
            
            // push errors.
            $this->errors->push($validation->errors());
        }
    }
}