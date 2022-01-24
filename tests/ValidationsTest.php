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
use Tobento\Service\Validation\Validations;
use Tobento\Service\Validation\Validation;
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Collection\Collection;

/**
 * ValidationsTest
 */
class ValidationsTest extends TestCase
{
    public function testThatImplementsValidationInterface()
    {
        $this->assertInstanceOf(
            ValidationInterface::class,
            new Validations(
                messagesFactory: new MessagesFactory(),
                data: [],
            )
        );
    }
    
    public function testIsValidMethod()
    {
        $validation = new Validations(
            new MessagesFactory(),
            ['foo' => 'value', 'bar' => 'value'],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => 'value'],
                key: 'foo',
                messagesFactory: null,
            ),
        );
        
        $this->assertTrue($validation->isValid());
    }
    
    public function testErrorsMethod()
    {
        $validation = new Validations(
            new MessagesFactory(),
            [],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => 'value'],
                key: 'foo',
                messagesFactory: null,
            ),
        );
        
        $this->assertFalse($validation->errors()->has());
        
        $validation = new Validations(
            new MessagesFactory(),
            [],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => ''],
                key: 'foo',
                messagesFactory: null,
            ),
        );
        
        $this->assertTrue($validation->errors()->has());        
    }
    
    public function testDataMethodValid()
    {
        $validation = new Validations(
            new MessagesFactory(),
            ['foo' => 'value', 'bar' => 'value'],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => 'value'],
                key: 'foo',
                messagesFactory: null,
            ),
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
        $validation = new Validations(
            new MessagesFactory(),
            ['foo' => 'value', 'bar' => ''],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => ''],
                key: 'foo',
                messagesFactory: null,
            ),
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
        $validation = new Validations(
            new MessagesFactory(),
            ['foo' => 'value', 'bar' => 'value'],
            new Validation(
                rule: new Rule\Same(),
                value: 'value',
                parameters: ['rule_parameters' => ['bar']],
                data: ['foo' => 'value', 'bar' => 'value'],
                key: 'foo',
                messagesFactory: null,
            ),
        );
        
        $this->assertSame(null, $validation->rule());
    }
}