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
use Tobento\Service\Validation\Validation;
use Tobento\Service\Validation\Rule;

/**
 * HasValidation
 */
trait HasValidation
{
    /**
     * @var null|ValidationInterface
     */
    protected null|ValidationInterface $validation = null;

    /**
     * Sets the validation.
     * 
     * @param ValidationInterface $validation
     * @return static $this
     */
    public function setValidation(ValidationInterface $validation): static
    {
        $this->validation = $validation;
        return $this;
    }
    
    /**
     * Returns the validation.
     * 
     * @return ValidationInterface
     */
    public function validation(): ValidationInterface
    {
        if (is_null($this->validation)) {
            $this->validation = new Validation(
                rule: new Rule\Fake(),
                value: null
            );
        }
        
        return $this->validation;
    }
}