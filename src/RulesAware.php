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
 * RulesAware
 */
interface RulesAware
{
    /**
     * Returns the rules.
     *
     * @return RulesInterface
     */
    public function rules(): RulesInterface;
}