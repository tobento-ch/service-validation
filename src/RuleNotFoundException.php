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

use Throwable;

/**
 * RuleNotFoundException
 */
class RuleNotFoundException extends RuleException
{
    /**
     * Create a new RuleNotFoundException.
     *
     * @param string $rule The rule name.
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected string $rule,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {        
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Returns the rule name.
     *
     * @return string
     */
    public function rule(): string
    {
        return $this->rule;
    }

    /**
     * Convert rule to string.
     *
     * @param mixed $rule
     * @return string
     */
    protected function convertRuleToString(mixed $rule): string
    {
        if (is_string($rule)) {
            return $rule;
        }
        
        if (is_object($rule)) {
            return $rule::class;
        }
        
        return '';
    }
}