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

use Tobento\Service\Validation\ValidationInterface;

/**
 * ValidationAware
 */
interface ValidationAware
{
    /**
     * Sets the validation.
     * 
     * @param ValidationInterface $validation
     * @return static $this
     */
    public function setValidation(ValidationInterface $validation): static;
    
    /**
     * Returns the validation.
     * 
     * @return ValidationInterface
     */
    public function validation(): ValidationInterface;
}