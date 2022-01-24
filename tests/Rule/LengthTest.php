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
use Tobento\Service\Validation\Rule\Length;
use Tobento\Service\Validation\RuleInterface;

/**
 * LengthTest
 */
class LengthTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Length()
        );
    }
    
    public function testMinMethod()
    {
        $rule = new Length();
        
        $this->assertTrue($rule->min('abc', [2]));
        
        $this->assertTrue($rule->min('abc', [3]));
        
        $this->assertFalse($rule->min('abc', [4]));
        
        $this->assertTrue($rule->min(' abc', [4]));
        
        $this->assertTrue($rule->min('    ', [4]));
        
        $this->assertTrue($rule->min('abc', []));
        
        $this->assertFalse($rule->min(false, [1]));
        
        $this->assertFalse($rule->min([], [1]));
        
        $this->assertTrue($rule->min('abc', ['3']));
        
        $this->assertTrue($rule->min('abc', ['a']));
        
        $this->assertTrue($rule->min(873, [3]));
        
        $this->assertTrue($rule->min(0, [1]));
        
        $this->assertTrue($rule->min(-1, [1]));
        
        $this->assertFalse($rule->min(873, [4]));
        
        $this->assertTrue($rule->min(1.05, [4]));
        
        $this->assertFalse($rule->min(1.05, [5]));
        
        $this->assertFalse($rule->min(1.00, [3]));
        
        $this->assertFalse($rule->min(['foo', 'bar'], [2]));
    }
    
    public function testMaxMethod()
    {
        $rule = new Length();
        
        $this->assertFalse($rule->max('abc', [2]));
        
        $this->assertTrue($rule->max('abc', [3]));
        
        $this->assertTrue($rule->max('abc', [4]));
        
        $this->assertTrue($rule->max(' abc', [4]));
        
        $this->assertTrue($rule->max('    ', [4]));
        
        $this->assertFalse($rule->max('abc', []));
        
        $this->assertFalse($rule->max(false, [1]));
        
        $this->assertFalse($rule->max([], [1]));
        
        $this->assertTrue($rule->max('abc', ['3']));
        
        $this->assertFalse($rule->max('abc', ['a']));
        
        $this->assertTrue($rule->max(873, [3]));
        
        $this->assertTrue($rule->max(0, [1]));
        
        $this->assertFalse($rule->max(-1, [1]));
        
        $this->assertTrue($rule->max(873, [4]));
        
        $this->assertTrue($rule->max(1.05, [4]));
        
        $this->assertTrue($rule->max(1.05, [5]));
        
        $this->assertTrue($rule->max(1.00, [3]));
        
        $this->assertFalse($rule->max(['foo', 'bar'], [2]));
    }    
}