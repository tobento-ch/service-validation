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
 * SkipValidationsAware
 */
interface SkipValidationsAware
{
    /**
     * Skips validations depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validations, otherwise false.
     */
    public function skipValidations(mixed $value, string $method = 'passes'): bool;
}