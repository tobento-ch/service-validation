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
use Tobento\Service\Validation\Validator;

/**
 * HasValidator
 */
trait HasValidator
{
    /**
     * @var null|ValidatorInterface
     */
    protected null|ValidatorInterface $validator = null;

    /**
     * Sets the validator.
     * 
     * @param ValidatorInterface $validator
     * @return static $this
     */
    public function setValidator(ValidatorInterface $validator): static
    {
        $this->validator = $validator;
        return $this;
    }
    
    /**
     * Returns the validator.
     * 
     * @return ValidatorInterface
     */
    public function validator(): ValidatorInterface
    {
        if (is_null($this->validator)) {
            $this->validator = new Validator();
        }
        
        return $this->validator;
    }
}