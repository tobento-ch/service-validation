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
 * RulesInterface
 */
interface RulesInterface
{
    /**
     * Add a rule.
     *
     * @param string $name
     * @param mixed $rule
     * @return static $this
     */
    public function add(string $name, mixed $rule): static;

    /**
     * Returns the rule based on the specified rule.
     *
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws RuleNotFoundException
     * @throws InvalidRuleException
     */
    public function get(mixed $rule): RuleInterface;
}