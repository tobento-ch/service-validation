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
use Tobento\Service\Collection\Collection;

/**
 * ValidationInterface
 */
interface ValidationInterface
{
    /**
     * Returns true if the validation is valid, otherwise false.
     *
     * @return bool
     */
    public function isValid(): bool;
    
    /**
     * Returns the errors.
     *
     * @return MessagesInterface
     */
    public function errors(): MessagesInterface;  
    
    /**
     * Returns the data.
     *
     * @return Collection
     */
    public function data(): Collection;
    
    /**
     * Returns the valid data.
     *
     * @return Collection
     */
    public function valid(): Collection;
    
    /**
     * Returns the invalid data.
     *
     * @return Collection
     */
    public function invalid(): Collection;
    
    /**
     * Returns the rule.
     *
     * @return null|RuleInterface
     */
    public function rule(): null|RuleInterface;
    
    /**
     * Returns the key if any.
     *
     * @return null|string
     */
    public function key(): null|string;    
}