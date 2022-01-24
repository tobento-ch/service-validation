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
use Tobento\Service\Validation\Rule\Number;
use Tobento\Service\Validation\RuleInterface;

/**
 * NumberTest
 */
class NumberTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Number()
        );
    }
    
    public function testDigitMethod()
    {
        $rule = new Number();
        
        $this->assertTrue($rule->digit('0'));
        
        $this->assertTrue($rule->digit('034'));
        
        $this->assertTrue($rule->digit(0));
        
        $this->assertTrue($rule->digit(56));
        
        $this->assertTrue($rule->digit(0x1A));
        
        $this->assertFalse($rule->digit(null));
        
        $this->assertFalse($rule->digit([]));
        
        $this->assertFalse($rule->digit('0a'));
        
        $this->assertFalse($rule->digit(' 45'));
        
        $this->assertFalse($rule->digit('45 '));
        
        $this->assertFalse($rule->digit(-1));
        
        $this->assertFalse($rule->digit('-1'));
        
        $this->assertFalse($rule->digit(false));
        
        $this->assertFalse($rule->digit(true));
        
        $this->assertFalse($rule->digit(new \DateTime()));
    }
    
    public function testDecimalMethod()
    {
        $rule = new Number();
        
        $this->assertTrue($rule->decimal('0'));
        
        $this->assertTrue($rule->decimal('034'));
        
        $this->assertTrue($rule->decimal('35'));
        
        $this->assertTrue($rule->decimal('35.000'));
        
        $this->assertTrue($rule->decimal('35.0001'));
        
        $this->assertTrue($rule->decimal('-1'));
        
        $this->assertTrue($rule->decimal(0));
        
        $this->assertTrue($rule->decimal(-0.00000));
        
        $this->assertTrue($rule->decimal(-0.00001));
        
        $this->assertTrue($rule->decimal(-1));
        
        $this->assertTrue($rule->decimal(+1));
        
        $this->assertTrue($rule->decimal(0x1A));
        
        $this->assertTrue($rule->decimal(1e7));
        
        $this->assertTrue($rule->decimal(23));
        
        $this->assertTrue($rule->decimal(23.000));
        
        $this->assertTrue($rule->decimal('+1'));
        
        $this->assertFalse($rule->decimal(' 67'));
        
        $this->assertFalse($rule->decimal('67 '));
                
        $this->assertFalse($rule->decimal(null));
        
        $this->assertFalse($rule->decimal([]));
        
        $this->assertFalse($rule->decimal('0a'));
        
        $this->assertFalse($rule->decimal(false));
        
        $this->assertFalse($rule->decimal(true));
        
        $this->assertFalse($rule->decimal(new \DateTime()));
    }
    
    public function testMinMethod()
    {
        $rule = new Number();
        
        $this->assertTrue($rule->min(1, [1]));
        
        $this->assertTrue($rule->min(1.001, [1.0001]));
        
        $this->assertTrue($rule->min(0.00, [0]));
        
        $this->assertFalse($rule->min(1.001, [1.002]));
        
        $this->assertTrue($rule->min('1', [1]));
        
        $this->assertTrue($rule->min('1.001', [1.0001]));
        
        $this->assertTrue($rule->min('0.00', [0]));
        
        $this->assertFalse($rule->min('1.001', [1.002]));
        
        $this->assertFalse($rule->min(null, [1]));
        
        $this->assertFalse($rule->min([], [1]));
        
        $this->assertFalse($rule->min('0a', [1]));
        
        $this->assertFalse($rule->min(false, [1]));
        
        $this->assertFalse($rule->min(true, [1]));
        
        $this->assertFalse($rule->min(new \DateTime(), [1]));        
    }
    
    public function testMaxMethod()
    {
        $rule = new Number();
        
        $this->assertTrue($rule->max(1, [1]));
        
        $this->assertFalse($rule->max(1.001, [1.0001]));
        
        $this->assertTrue($rule->max(0.00, [0]));
        
        $this->assertTrue($rule->max(1.001, [1.002]));
        
        $this->assertTrue($rule->max('1', [1]));
        
        $this->assertFalse($rule->max('1.001', [1.0001]));
        
        $this->assertTrue($rule->max('0.00', [0]));
        
        $this->assertTrue($rule->max('1.001', [1.002]));
        
        $this->assertFalse($rule->max(null, [1]));
        
        $this->assertFalse($rule->max([], [1]));
        
        $this->assertFalse($rule->max('0a', [1]));
        
        $this->assertFalse($rule->max(false, [1]));
        
        $this->assertFalse($rule->max(true, [1]));
        
        $this->assertFalse($rule->max(new \DateTime(), [1]));        
    }    
}