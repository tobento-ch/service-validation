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

namespace Tobento\Service\Validation\Rule;

use Tobento\Service\Validation\ValidatorInterface;

/**
 * ValidatorAware
 */
interface ValidatorAware
{
    /**
     * Sets the validator.
     * 
     * @param ValidatorInterface $validator
     * @return static $this
     */
    public function setValidator(ValidatorInterface $validator): static;
    
    /**
     * Returns the validator.
     * 
     * @return ValidatorInterface
     */
    public function validator(): ValidatorInterface;
}