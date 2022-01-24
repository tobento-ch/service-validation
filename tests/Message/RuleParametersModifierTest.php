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

namespace Tobento\Service\Validation\Test\Message;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Validation\Message\RuleParametersModifier;
use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\Message;

/**
 * RuleParametersModifierTest
 */
class RuleParametersModifierTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new RuleParametersModifier()
        );     
    }

    public function testModify()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: [':attribute' => 'The title'],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            ':attribute is invalid',
            $newMessage->message()
        );
        
        $this->assertSame(
            [
                ':attribute' => 'The title',
                ':value' => '',
            ],
            $newMessage->parameters()
        );        
        
        $this->assertFalse($message === $newMessage);
    }
    
    public function testAttributeIsNotSetWithoutKey()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: [],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testAttributeIsNotSetWithKey()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: [],
            key: 'title',
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => 'title',
                ':value' => '',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testValueString()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute and :value',
            context: ['value' => 'foo'],
        );
        
        $newMessage = $modifier->modify($message);

        $this->assertSame(
            ':attribute and :value',
            $newMessage->message()
        );
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => 'foo',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testValueInt()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: ['value' => 15],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '15',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testValueFloat()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: ['value' => 15.45],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '15.45',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testValueBool()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: ['value' => true],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '1',
            ],
            $newMessage->parameters()
        );
    }    
    
    public function testValueArray()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute is invalid',
            context: ['value' => []],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '[array]',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testParams()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute and :parameters',
            context: ['rule_parameters' => ['red', 'blue']],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            ':attribute and red, blue',
            $newMessage->message()
        );
        
        $this->assertSame(
            [
                ':attribute' => '',
                ':value' => '',
            ],
            $newMessage->parameters()
        );
    }
    
    public function testParamsEach()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute and :parameters[0] as :parameters[1]',
            context: ['rule_parameters' => ['red', 'blue']],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            ':attribute and red as blue',
            $newMessage->message()
        );
    }
    
    public function testParamsWithSkipFirst()
    {
        $modifier = new RuleParametersModifier();

        $message = new Message(
            level: 'error',
            message: ':attribute and :parameters[-1]',
            context: ['rule_parameters' => ['red', 'blue']],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            ':attribute and blue',
            $newMessage->message()
        );
    }
    
    public function testThatParametersAreAddedToMessages()
    {
        $modifier = new RuleParametersModifier(
            ['count', 'limit_length'],
        );

        $message = new Message(
            level: 'error',
            message: 'message',
            context: [
                'rule_parameters' => [],
                'count' => 5,
                'limit_length' => 20,
                'error' => 'Message',
                ':attribute' => 'foo',
            ],
        );
        
        $newMessage = $modifier->modify($message);
        
        $this->assertSame(
            [
                'count' => 5,
                'limit_length' => 20,
                ':attribute' => 'foo',
                ':value' => '',
            ],
            $newMessage->parameters()
        );
    }    
}