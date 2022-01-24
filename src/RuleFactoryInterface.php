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
 * RuleFactoryInterface
 */
interface RuleFactoryInterface
{
    /**
     * Returns the created rule.
     *
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws InvalidRuleException
     */
    public function createRule(mixed $rule): RuleInterface;
}