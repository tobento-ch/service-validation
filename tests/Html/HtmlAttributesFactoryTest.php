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

namespace Tobento\Service\Validation\Test\Html;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Validation\Html\HtmlAttributesFactory;
use Tobento\Service\Validation\Html\HtmlAttributesFactoryInterface;

class HtmlAttributesFactoryTest extends TestCase
{    
    public function testThatImplementsHtmlAttributesFactoryInterface()
    {
        $this->assertInstanceOf(
            HtmlAttributesFactoryInterface::class,
            new HtmlAttributesFactory()
        );
    }

    public function testThrowsInvalidArgumentExceptionIfDataAttributeNotStartingWithData()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(
            rules: 'alnum|alpha',
            inputType: 'text',
            inputName: 'foo',
            messageDataAttributeName: 'name',
        );
    }
    
    public function testWithCustomMessageAttributeName()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(
            rules: 'maxLen:5',
            inputType: 'text',
            inputName: 'foo',
            messageDataAttributeName: 'data-errors'
        );
        
        $this->assertTrue(array_key_exists('data-errors', $attributes));
    }

    public function testUnsupportedRulesAreIgnored()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'array', inputType: 'text', inputName: 'foo');
        
        $this->assertSame([], $attributes);
    }

    public function testSameNameAttributesAreAddedOnceOnly()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'notEmpty|required', inputType: 'text', inputName: 'foo');
        
        $this->assertSame(['required'], $attributes);
    }
    
    public function testMessageUsesModifiers()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'maxLen:5', inputType: 'text', inputName: 'foo');
        
        $this->assertSame(
            'The foo must at most contain 5 chars.',
            $attributes['data-validation-messages']['maxlength'] ?? null
        );
    }
    
    public function testCustomMessageIsUsedIfDefined()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(
            rules: [['maxLen:5', 'error' => 'Custom error message :parameters[0]']],
            inputType: 'text',
            inputName: 'foo'
        );
        
        $this->assertSame(
            'Custom error message 5',
            $attributes['data-validation-messages']['maxlength'] ?? null
        );
    }
    
    public function testWithoutMessages()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(
            rules: 'maxLen:5',
            inputType: 'text',
            inputName: 'foo',
            messageDataAttributeName: null
        );
        
        $this->assertFalse(array_key_exists('data-validation-messages', $attributes));
    }
    
    public function testAlnumRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'alnum', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('[a-zA-Z0-9]+', $attributes['pattern'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['pattern'] ?? null);
    }
    
    public function testAlphaRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'alpha', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('[a-zA-Z]+', $attributes['pattern'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['pattern'] ?? null);
    }
    
    public function testAlphabeticRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'alphabetic', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('[\pL\pM]+', $attributes['pattern'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['pattern'] ?? null);
    }
    
    public function testAlphabeticNumRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'alphabeticNum', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('[\pL\pM\pN]+', $attributes['pattern'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['pattern'] ?? null);
    }
    
    public function testMaxLenRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'maxLen:5', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('5', $attributes['maxlength'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['maxlength'] ?? null);
    }
    
    public function testMaxNumRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'maxNum:5', inputType: 'number', inputName: 'foo');
        
        $this->assertSame('5', $attributes['max'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['max'] ?? null);
    }

    public function testMinLenRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'minLen:5', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('5', $attributes['minlength'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['minlength'] ?? null);
    }
    
    public function testMinNumRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'minNum:5', inputType: 'number', inputName: 'foo');
        
        $this->assertSame('5', $attributes['min'] ?? null);
        $this->assertNotEmpty($attributes['data-validation-messages']['min'] ?? null);
    }
    
    public function testNotEmptyRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'notEmpty', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('required', $attributes[0] ?? null);
        $this->assertNull($attributes['data-validation-messages']['required'] ?? null);
    }
    
    public function testNotNullRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'notNull', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('required', $attributes[0] ?? null);
        $this->assertNull($attributes['data-validation-messages']['required'] ?? null);
    }
    
    public function testRequiredRule()
    {
        $factory = new HtmlAttributesFactory();
        $attributes = $factory->createAttributes(rules: 'required', inputType: 'text', inputName: 'foo');
        
        $this->assertSame('required', $attributes[0] ?? null);
        $this->assertNull($attributes['data-validation-messages']['required'] ?? null);
    }
}