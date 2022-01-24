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
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Validation\RuleException;
use Tobento\Service\Collection\Collection;

/**
 * ValidatorValidatingMethodTest
 */
class ValidatorValidatingMethodTest extends TestCase
{
    public function testMultipleRulesIsValid()
    {
        $validation = (new Validator())->validating(
            value: 'foo',
            rules: 'alpha|minLen:2',
            data: [],
            key: null
        );
        
        $this->assertInstanceOf(
            ValidationInterface::class,
            $validation
        );       
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsValidIfEmptyStringValue()
    {
        $validation = (new Validator())->validating(
            value: '',
            rules: 'alpha|minLen:2',
            data: [],
            key: null
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsValidIfNullValue()
    {
        $validation = (new Validator())->validating(
            value: null,
            rules: 'alpha|minLen:2',
            data: [],
            key: null
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsInvalidIfEmptyValueWitRequiredRule()
    {
        $validation = (new Validator())->validating(
            value: null,
            rules: 'required|alpha|minLen:2',
            data: [],
            key: null
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }    
    
    public function testOneRuleFails()
    {
        $validation = (new Validator())->validating(
            value: 'f2oo',
            rules: 'alpha|minLen:2',
            data: [],
            key: null
        );
                
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(1, count($validation->errors()->all()));
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMoreRulesFails()
    {
        $validation = (new Validator())->validating(
            value: '2',
            rules: 'alpha|minLen:2',
            data: [],
            key: null
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(2, count($validation->errors()->all()));
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testWithDataAndKeyShouldSetInvalidAndValidData()
    {
        $validation = (new Validator())->validating(
            value: 40,
            rules: 'same:foo',
            data: [
                'bar' => 40,
                'foo' => 40,
            ],
            key: 'bar'
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(
            [
                'bar' => 40,
                'foo' => 40,                
            ],
            $validation->data()->all()
        );
        
        $this->assertSame(['bar' => 40], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testWithDataButWithoutKeyDoesNotSetInvalidAndValidData()
    {
        $validation = (new Validator())->validating(
            value: 40,
            rules: 'same:foo',
            data: [
                'bar' => 40,
                'foo' => 40,
            ],
            key: null
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(
            [
                'bar' => 40,
                'foo' => 40,                
            ],
            $validation->data()->all()
        );
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testWithDataAndKeyErrorsShouldBeKeyed()
    {
        $validation = (new Validator())->validating(
            value: 40,
            rules: 'same:foo',
            data: [
                'bar' => 40,
                'foo' => 50,
            ],
            key: 'bar'
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertTrue($validation->errors()->key('bar')->has());
        
        $this->assertSame(
            [
                'bar' => 40,
                'foo' => 50,                
            ],
            $validation->data()->all()
        );
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame(['bar' => 40], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }    

    public function testWithDataCollection()
    {
        $validation = (new Validator())->validating(
            value: 40,
            rules: 'same:foo',
            data: new Collection([
                'bar' => 40,
                'foo' => 50,
            ]),
            key: 'bar'
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertTrue($validation->errors()->key('bar')->has());
        
        $this->assertSame(
            [
                'bar' => 40,
                'foo' => 50,                
            ],
            $validation->data()->all()
        );
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame(['bar' => 40], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    } 
    
    public function testThrowsRuleExceptionIfRuleDoesNotExist()
    {
        $this->expectException(RuleException::class);
        
        $validation = (new Validator())->validating(
            value: 'foo',
            rules: 'unknown|minLen:2',
            data: [],
            key: null
        );
    }
    
    public function testDefArrayWithStringRule()
    {
        $validation = (new Validator())->validating(
            value: 'foo',
            rules: [
                'required',
                ['minLen:3']
            ],
            data: [],
            key: null
        );
        
        $this->assertInstanceOf(
            ValidationInterface::class,
            $validation
        );       
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testDefArrayWithStringRuleCustomError()
    {
        $validation = (new Validator())->validating(
            value: 'foo',
            rules: [
                'required',
                ['minLen:4', 'error' => 'Error Message']
            ],
            data: [],
            key: null
        );
        
        $this->assertInstanceOf(
            ValidationInterface::class,
            $validation
        );       
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(
            'Error Message',
            $validation->errors()->first()->message()
        );
        
        $this->assertSame([], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }   
}