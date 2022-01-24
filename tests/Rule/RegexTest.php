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

namespace Tobento\Service\Validation\Test\Rule;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Validation\Rule\Regex;
use Tobento\Service\Validation\RuleInterface;

/**
 * RegexTest
 */
class RegexTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Regex()
        );
    }
    
    public function testPassesMethod()
    {
        $rule = new Regex();

        $this->assertTrue($rule->passes('foo23', ['#^[a-z0-9]+$#']));
        
        $this->assertFalse($rule->passes('foo23', ['#^[a-z]+$#']));
    }
}