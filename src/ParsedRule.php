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

/**
 * ParsedRule
 */
class ParsedRule
{
    /**
     * Create a new ParsedRule.
     * 
     * @param mixed $rule
     * @param array $parameters The rule parameters if any.
     */
    public function __construct(
        private mixed $rule,
        private array $parameters = [],
    ) {}
    
    /**
     * Returns the rule.
     *
     * @return mixed
     */
    public function rule(): mixed
    {
        return $this->rule;
    }

    /**
     * Returns the rule parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}