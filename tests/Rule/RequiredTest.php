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
use Tobento\Service\Validation\Rule\Required;
use Tobento\Service\Validation\Rule\Fake;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Validation;

/**
 * RequiredTest
 */
class RequiredTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Required()
        );
    }
    
    public function testIfNotEmptyMethod()
    {
        $rule = new Required();

        $this->assertTrue($rule->ifNotEmpty('v'));
        
        $this->assertTrue($rule->ifNotEmpty('1'));
        
        $this->assertTrue($rule->ifNotEmpty(['f']));
        
        $this->assertTrue($rule->ifNotEmpty(1));
        
        $this->assertFalse($rule->ifNotEmpty(''));
        
        $this->assertFalse($rule->ifNotEmpty([]));
        
        $this->assertFalse($rule->ifNotEmpty(0));
        
        $this->assertFalse($rule->ifNotEmpty(null));
    }
    
    public function testIfInMethod()
    {
        $rule = new Required();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                ],
            )        
        );
        
        $this->assertFalse($rule->ifIn(null, ['field', 'mr', 'ms']));
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                ],
            )        
        );
        
        $this->assertTrue($rule->ifIn(null, ['field', 'ms']));
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                ],
            )        
        );
        
        $this->assertTrue($rule->ifIn(null, ['field', 'ms']));        
    }
    
    public function testWithoutMethod()
    {
        $rule = new Required();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => '',
                ],
            )        
        );
        
        $this->assertFalse($rule->without(null, ['foo', 'bar']));
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => 'f',
                ],
            )        
        );
        
        $this->assertFalse($rule->without(null, ['foo', 'bar']));        
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => 'f',
                    'bar' => 'b',
                ],
            )        
        );
        
        $this->assertTrue($rule->without(null, ['foo', 'bar']));        
    }
    
    public function testWithMethod()
    {
        $rule = new Required();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => '',
                ],
            )        
        );
        
        $this->assertTrue($rule->with(null, ['foo', 'bar']));

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => 'f',
                ],
            )        
        );
        
        $this->assertFalse($rule->with(null, ['foo', 'bar'])); 
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                    'foo' => 'f',
                    'bar' => 'b',
                ],
            )        
        );
        
        $this->assertFalse($rule->with(null, ['foo', 'bar']));
    }
    
    public function testIfEqualMethod()
    {
        $rule = new Required();

        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                ],
            )
        );
        
        $this->assertFalse($rule->ifEqual(null, ['field', 'mr']));
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                    'field' => 'mr',
                ],
            )        
        );
        
        $this->assertTrue($rule->ifEqual(null, ['field', 'ms']));
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: [
                ],
            )        
        );
        
        $this->assertTrue($rule->ifEqual(null, ['field', 'ms']));        
    }
}