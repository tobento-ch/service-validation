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
use Tobento\Service\Validation\Rule\Sometimes;
use Tobento\Service\Validation\Rule\Fake;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Validation;

class SometimesTest extends TestCase
{
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Sometimes()
        );
    }
    
    public function testNotSkippingValidationsIfDataKeyIsPresent()
    {
        $rule = new Sometimes();
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: ['field' => 'value'],
                key: 'field',
            )
        );
        
        $this->assertFalse($rule->skipValidations('value'));
        $this->assertTrue($rule->skipValidation('value'));
        $this->assertFalse($rule->passes('value'));
    }
    
    public function testSkipsValidationsIfDataKeyIsNotPresent()
    {
        $rule = new Sometimes();
        
        $rule->setValidation(
            new Validation(
                rule: new Fake(),
                value: null,
                data: ['field' => 'value'],
                key: 'foo',
            )
        );
        
        $this->assertTrue($rule->skipValidations('value'));
        $this->assertTrue($rule->skipValidation('value'));
        $this->assertTrue($rule->passes('value'));
    }
}