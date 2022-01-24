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

namespace Tobento\Service\Validation\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Validation\CallableRule;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\Test\Mock\MissingMessageRule;

/**
 * CallableRuleTest
 */
class CallableRuleTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new CallableRule(new Rule\Strings(), 'alpha')
        );
    }

    public function testRuleMethod()
    {
        $stringsRule = new Rule\Strings();
        $rule = new CallableRule($stringsRule, 'alpha');
        
        $this->assertSame($stringsRule, $rule->rule());
    }
    
    public function testMethodMethod()
    {
        $rule = new CallableRule(new Rule\Strings(), 'alpha');
        
        $this->assertSame('alpha', $rule->method());
    }    
    
    public function testPassesMethod()
    {
        $rule = new CallableRule(new Rule\Strings(), 'alpha');
        
        $this->assertTrue($rule->passes('value'));
        $this->assertFalse($rule->passes('45s'));
    }
    
    public function testMessagesMethodReturnsRuleMessage()
    {
        $rule = new CallableRule(new Rule\Strings(), 'alpha');
        
        $this->assertSame(
            [
                0 => 'The :attribute must only contain letters [a-zA-Z]',
            ],
            $rule->messages()
        );
    }
    
    public function testMessagesMethodReturnsDefaultMessageIfRuleMessageDoesNotExist()
    {
        $rule = new CallableRule(new MissingMessageRule(), 'alpha');
        
        $this->assertSame(
            [
                0 => 'The :attribute is invalid.',
            ],
            $rule->messages()
        );
    }    
}