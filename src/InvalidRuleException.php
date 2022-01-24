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
 * InvalidMiddlewareException
 */
class InvalidRuleException extends RuleException
{
    /**
     * Create a new InvalidRuleException.
     *
     * @param mixed $rule
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected mixed $rule,
        string $message = '',
        int $code = 0,
        null|Throwable $previous = null
    ) {
        if ($message === '') {
            
            $rule = $this->convertRuleToString($rule);
            
            $message = 'Rule ['.$rule.'] is invalid';    
        }
        
        parent::__construct($message, $code, $previous);
    }
    
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