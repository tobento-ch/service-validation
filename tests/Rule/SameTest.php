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
use Tobento\Service\Validation\Rule\Same;
use Tobento\Service\Validation\Rule\Fake;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Validation;

/**
 * SameTest
 */
class SameTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Same()
        );
    }
    
    public function testPassesMethod()
    {
        $rule = new Same();
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: ['field' => 'foo'],
            )        
        );
        
        $this->assertTrue($rule->passes('foo', ['field']));
        
        $this->assertFalse($rule->passes('bar', ['field']));
    }
    
    public function testPassesMethodWithNumber()
    {
        $rule = new Same();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: ['field' => 45],
            )        
        );
        
        $this->assertTrue($rule->passes(45, ['field']));
        
        $this->assertFalse($rule->passes(55, ['field']));
    }
    
    public function testPassesMethodWithArray()
    {
        $rule = new Same();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: ['field' => ['foo']],
            )        
        );
        
        $this->assertTrue($rule->passes(['foo'], ['field']));
        
        $this->assertFalse($rule->passes(['bar'], ['field']));
    }    
}