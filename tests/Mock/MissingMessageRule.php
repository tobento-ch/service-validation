<?php

/**
 * TOBENTO
 *
 * @copyright    Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Validation\Test\Mock;

use Tobento\Service\Validation\Rule\Rule;

/**
 * MissingMessageRule
 */
class MissingMessageRule extends Rule
{    
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => ':attribute and :parameters[0] must match.',
    ];
    
    public function passes(mixed $value, array $parameters = []): bool
    {
        return true;
    }
    
    public function alpha(mixed $value, array $parameters = []): bool
    {
        return true;
    }    
}