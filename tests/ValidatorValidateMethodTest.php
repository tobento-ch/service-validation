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
 * ValidatorValidateMethodTest
 */
class ValidatorValidateMethodTest extends TestCase
{
    public function testMultipleRulesIsValid()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'Product',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertInstanceOf(
            ValidationInterface::class,
            $validation
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(['title' => 'Product', 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['title' => 'Product', 'color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsValidIfEmptyStringValue()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => '',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(['title' => '', 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['title' => '', 'color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsValidIfNullValue()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => null,
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(['title' => null, 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['title' => null, 'color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMultipleRulesIsInvalidIfEmptyValueWitRequiredRule()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => null,
                'color' => 'blue',
            ],
            rules: [
                'title' => 'required|alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(['title' => null, 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame(['title' => null], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }    
    
    public function testOneRuleFails()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'f2oo',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
                
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(1, count($validation->errors()->all()));
        
        $this->assertSame(['title' => 'f2oo', 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame(['title' => 'f2oo'], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMoreRulesFails()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => '2',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(2, count($validation->errors()->all()));
        
        $this->assertSame(['title' => '2', 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame(['color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame(['title' => '2'], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testMoreDataItemsFail()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => '2',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:red:green',        
            ]
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertTrue($validation->errors()->key('title')->has());
        
        $this->assertTrue($validation->errors()->key('color')->has());
        
        $this->assertSame(3, count($validation->errors()->all()));
        
        $this->assertSame(['title' => '2', 'color' => 'blue'], $validation->data()->all());
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame(['title' => '2', 'color' => 'blue'], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testValidDataShouldContainTheValidatedValidData()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'product',
                'color' => 'blue',
                'desc' => 'lorem',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(
            ['title' => 'product', 'color' => 'blue', 'desc' => 'lorem'],
            $validation->data()->all()
        );
        
        $this->assertSame(['title' => 'product', 'color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testInValidDataShouldContainTheInValidData()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'p',
                'color' => 'blue',
                'desc' => 'lorem',
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
        
        $this->assertFalse($validation->isValid());
        
        $this->assertTrue($validation->errors()->has());
        
        $this->assertSame(
            ['title' => 'p', 'color' => 'blue', 'desc' => 'lorem'],
            $validation->data()->all()
        );
        
        $this->assertSame(['color' => 'blue'], $validation->valid()->all());
        
        $this->assertSame(['title' => 'p'], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testWithNoRulesShouldBeValid()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'p',
                'color' => 'blue',
            ],
            rules: []
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(
            ['title' => 'p', 'color' => 'blue'],
            $validation->data()->all()
        );
        
        $this->assertSame([], $validation->valid()->all());
        
        $this->assertSame([], $validation->invalid()->all());
        
        $this->assertSame(null, $validation->rule());
    }
    
    public function testNestedData()
    {
        $validation = (new Validator())->validate(
            data: [
                'title' => 'product',
                'meta' => [
                    'color' => 'blue',
                    'weight' => 5,
                ],
            ],
            rules: [
                'title' => 'alpha|minLen:2',
                'meta.color' => 'required|in:blue:red:green',        
            ]
        );
        
        $this->assertTrue($validation->isValid());
        
        $this->assertFalse($validation->errors()->has());
        
        $this->assertSame(
            [
                'title' => 'product',
                'meta' => [
                    'color' => 'blue',
                    'weight' => 5,
                ],
            ],
            $validation->data()->all()
        );
        
        $this->assertSame(
            [
                'title' => 'product',
                'meta' => [
                    'color' => 'blue',
                ],
            ],
            $validation->valid()->all()
        );
        
        $this->assertSame(
            [],
            $validation->invalid()->all()
        );
    }    
    
    public function testThrowsRuleExceptionIfRuleDoesNotExist()
    {
        $this->expectException(RuleException::class);

        $validation = (new Validator())->validate(
            data: [
                'title' => '2',
                'color' => 'blue',
            ],
            rules: [
                'title' => 'unknown|minLen:2',
                'color' => 'in:blue:red:green',        
            ]
        );
    }    
}