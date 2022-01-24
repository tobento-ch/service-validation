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

use Tobento\Service\Validation\RuleInterface;

/**
 * Rule
 */
abstract class Rule implements RuleInterface
{
    use IsEmpty;

    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => 'The :attribute is invalid.',
    ];
    
    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool
    {
        return $this->isEmpty($value);
    }
    
    /**
     * Determine if the validation rule passes.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        return false;
    }
    
    /**
     * Returns the validation error messages.
     * 
     * @return array
     */
    public function messages(): array
    {
        return static::MESSAGES;
    }    
}