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
 * IsEmpty
 */
trait IsEmpty
{
    /**
     * Returns true if value is empty, otherwise false.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function isEmpty(mixed $value): bool
    {
        if (is_string($value) && $value !== '') {
            return false;
        }
        
        if (
            is_bool($value)
            && in_array($value, [true, false, 0, 1, '0', '1'], true)
        ) {
            return false;
        }
        
        
        if (is_array($value)) {
            return false;
        }
        
        if (is_float($value)) {
            return false;
        }

        return empty($value);
    }
}