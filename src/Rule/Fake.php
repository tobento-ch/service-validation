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

/**
 * Fake
 */
class Fake extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [];
    
    /**
     * Determine if the value is the same of the field provided by parameter.
     * same:field, same:user.password
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        return true;
    }
}