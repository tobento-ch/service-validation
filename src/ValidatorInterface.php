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

use Tobento\Service\Collection\Collection;

/**
 * ValidatorInterface
 */
interface ValidatorInterface
{
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
    ): ValidationInterface;
    
    /**
     * Validate the data given.
     * 
     * @param mixed $data
     * @param array $rules
     * @return ValidationInterface
     *
     * @throws RuleException
     */
    public function validate(mixed $data, array $rules): ValidationInterface;    
}