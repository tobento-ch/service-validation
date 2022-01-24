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
use Tobento\Service\Validation\Validation;
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Collection\Collection;

/**
 * ValidationTest
 */
class ValidationTest extends TestCase
{
    public function testThatImplementsValidationInterface()
    {
        $this->assertInstanceOf(
            ValidationInterface::class,
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => 'value'],
                key: 'foo',
                messagesFactory: null,
            )
        );
    }
    
    public function testIsValidMethod()
    {
        $validation = new Validation(
            rule: new Rule\Same(),
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => 'value'],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertTrue($validation->isValid());
    }
    
    public function testErrorsMethod()
    {
        $validation = new Validation(
            rule: new Rule\Same(),
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => 'value'],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertFalse($validation->errors()->has());
        
        $validation = new Validation(
            rule: new Rule\Same(),
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => ''],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertTrue($validation->errors()->has());        
    }
    
    public function testDataMethodValid()
    {
        $validation = new Validation(
            rule: new Rule\Same(),
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => 'value'],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertSame(
            [
                'foo' => 'value',
                'bar' => 'value',
            ],
            $validation->data()->all()
        );
        
        $this->assertSame(
            [
                'foo' => 'value',          
            ],
            $validation->valid()->all()
        );
        
        $this->assertSame(
            [],
            $validation->invalid()->all()
        );        
    }
    
    public function testDataMethodInvalid()
    {
        $validation = new Validation(
            rule: new Rule\Same(),
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => ''],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertSame(
            [
                'foo' => 'value',
                'bar' => '',
            ],
            $validation->data()->all()
        );
        
        $this->assertSame(
            [
                //
            ],
            $validation->valid()->all()
        );
        
        $this->assertSame(
            [
                'foo' => 'value',
            ],
            $validation->invalid()->all()
        );        
    }
    
    public function testRuleMethod()
    {
        $rule = new Rule\Same();
        
        $validation = new Validation(
            rule: $rule,
            value: 'value',
            parameters: ['rule_parameters' => ['bar']],
            data: ['foo' => 'value', 'bar' => 'value'],
            key: 'foo',
            messagesFactory: null,
        );
        
        $this->assertSame($rule, $validation->rule());
    }    
}