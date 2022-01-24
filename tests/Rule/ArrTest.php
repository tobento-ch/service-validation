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
use Tobento\Service\Validation\Rule\Arr;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Validator;

/**
 * ArrTest
 */
class ArrTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Arr()
        );
    }
    
    public function testInMethod()
    {
        $rule = new Arr();
        
        $this->assertTrue($rule->in('red', ['red', 'blue']));
        
        $this->assertTrue($rule->in('blue', ['red', 'blue']));
        
        $this->assertFalse($rule->in('', ['red', 'blue']));
        
        $this->assertTrue($rule->in(45, [45]));
                
        $this->assertFalse($rule->in('yellow', ['red', 'blue']));
        
        $this->assertFalse($rule->in('yellow', []));
        
        $this->assertFalse($rule->in('yellow'));
        
        $this->assertFalse($rule->in(new \DateTime(), [45]));
        
        $this->assertFalse($rule->in(null, [45]));
        
        $this->assertTrue($rule->in(true, [true]));
    }
    
    public function testEachMethod()
    {
        $rule = new Arr();
 
        $this->assertTrue($rule->each(
            [5, 8],
            [5, 8]
        ));
        
        $this->assertFalse($rule->each(
            [5, 8],
            [5, '8']
        ));
        
        $this->assertFalse($rule->each(
            [5, 8],
            [5, 7]
        ));

        $this->assertFalse($rule->each(
            ['red', 'red'],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->each(
            ['red', 'yellow'],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->each(
            ['red', []],
            ['red', 'blue']
        ));        
        
        $this->assertTrue($rule->each(
            [],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->each('yellow'));
        
        $this->assertFalse($rule->each(new \DateTime(), [45]));
        
        $this->assertFalse($rule->each(null, [45]));
        
        $this->assertFalse($rule->each(true, [true]));        
    }
    
    public function testEachMethodValidatesKeysBasedOnParamsKeys()
    {
        $rule = new Arr();   
        
        $this->assertTrue($rule->each(
            ['red' => 'Red'],
            ['red' => 'Red', 'blue' => 'Blue']
        ));
        
        $this->assertTrue($rule->each(
            ['red' => 'Red', 'blue' => 'Blue'],
            ['red' => 'Red', 'blue' => 'Blue']
        ));        
        
        $this->assertTrue($rule->each(
            ['blue' => 'Blue'],
            ['red' => 'Red', 'blue' => 'Blue']
        ));        
        
        $this->assertFalse($rule->each(
            ['red' => 'blue'],
            ['red' => 'Red', 'blue' => 'blue']
        ));
        
        $this->assertFalse($rule->each(
            ['red' => 'red'],
            ['red' => 'Red', 'blue' => 'blue']
        ));        
        
        $this->assertFalse($rule->each(
            ['red' => 56],
            ['red' => 'Red', 'blue' => 'blue']
        ));
        
        $this->assertFalse($rule->each(
            [5 => 'red'],
            ['red' => 'Red', 'blue' => 'blue']
        ));        
        
        $this->assertFalse($rule->each(
            ['red' => 56],
            ['red' => 'Red', 'blue' => 'blue']
        ));        
    }
    
    public function testEachMethodAddsErrorsOnValueFailure()
    {
        $rule = new Arr();  
        
        $rule->each(
            ['red' => 'blue'],
            ['red' => 'Red', 'blue' => 'blue']
        );
        
        $rule->each(
            ['red' => 'yellow'],
            ['red' => 'Red', 'blue' => 'blue']
        );
        
        $rule->each(
            ['yellow' => 'Red'],
            ['red' => 'Red', 'blue' => 'blue']
        );        
            
        $this->assertSame(
            2,
            count($rule->validation()->errors()->all())
        );            
    }   

    public function testEachInMethod()
    {
        $rule = new Arr();
 
        $this->assertTrue($rule->eachIn(
            [5, 8],
            [5, 8]
        ));
        
        $this->assertFalse($rule->eachIn(
            [5, 8],
            [5, '8']
        ));
        
        $this->assertFalse($rule->eachIn(
            [5, 8],
            [5, 7]
        ));

        $this->assertTrue($rule->eachIn(
            ['red', 'red'],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->eachIn(
            ['red', 'yellow'],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->eachIn(
            ['red', []],
            ['red', 'blue']
        ));        
        
        $this->assertTrue($rule->eachIn(
            [],
            ['red', 'blue']
        ));
        
        $this->assertFalse($rule->eachIn('yellow'));
        
        $this->assertFalse($rule->eachIn(new \DateTime(), [45]));
        
        $this->assertFalse($rule->eachIn(null, [45]));
        
        $this->assertFalse($rule->eachIn(true, [true]));        
    }
    
    public function testEachWithMethod()
    {
        $rule = new Arr();
        $rule->setValidator(new Validator());
        
        $this->assertTrue($rule->eachWith(
            ['red'],
            ['key' => 'int', 'value' => 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            [],
            ['key' => 'int', 'value' => 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            ['red', 'blue'],
            ['key' => 'int', 'value' => 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['red', 45],
            ['key' => 'int', 'value' => 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            ['red', ''],
            ['key' => 'int', 'value' => 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['red', ''],
            ['key' => 'int', 'value' => 'required|alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['foo' => 'red', 'blue'],
            ['key' => 'int', 'value' => 'required|alpha']
        ));        
        
        $this->assertFalse($rule->eachWith(
            ['red'],
            []
        ));
        
        $this->assertFalse($rule->eachWith('yellow'));
        
        $this->assertFalse($rule->eachWith(new \DateTime(), [45]));
        
        $this->assertFalse($rule->eachWith(null, [45]));
        
        $this->assertFalse($rule->eachWith(true, [true]));        
    }
    
    public function testEachWithMethodWithStringParams()
    {
        $rule = new Arr();
        $rule->setValidator(new Validator());
        
        $this->assertTrue($rule->eachWith(
            ['red'],
            ['int', 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            [],
            ['int', 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            ['red', 'blue'],
            ['int', 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['red', 45],
            ['int', 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['red', 'blue'],
            ['required/int/minNum;1', 'alpha']
        ));
        
        $this->assertTrue($rule->eachWith(
            [1 => 'red', 2 => 'blue'],
            ['required/int/minNum;1', 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            [1 => 'red', 2 => 'blue'],
            ['int', 'alpha/maxLen;3']
        ));        
        
        $this->assertTrue($rule->eachWith(
            ['red', ''],
            ['int', 'alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['red', ''],
            ['int', 'required/alpha']
        ));
        
        $this->assertFalse($rule->eachWith(
            ['foo' => 'red', 'blue'],
            ['int', 'required/alpha']
        ));        
        
        $this->assertFalse($rule->eachWith(
            ['red'],
            []
        ));        
    }
    
    public function testEachWithMethodAddsErrorsOnValueFailure()
    {
        $rule = new Arr();  
        
        $rule->eachWith(
            ['red', ''],
            ['int', 'required/alpha']
        );
        
        $rule->eachWith(
            ['5' => ''],
            ['int', 'required/alpha']
        );
        
        $rule->eachWith(
            ['red', 'blue'],
            ['required/int/minNum;1', 'alpha']
        );        
            
        $this->assertSame(
            2,
            count($rule->validation()->errors()->all())
        );            
    }    
    
    public function testMinMethod()
    {
        $rule = new Arr();
        
        $this->assertTrue($rule->min(
            ['red'],
            [1]
        ));
        
        $this->assertTrue($rule->min(
            ['red'],
            ['1']
        ));        
        
        $this->assertFalse($rule->min(
            ['red'],
            [2]
        ));
        
        $this->assertFalse($rule->min(
            ['red'],
            ['2']
        ));
        
        $this->assertFalse($rule->min(
            [],
            []
        ));
        
        $this->assertTrue($rule->min(
            ['blue'],
            []
        ));
        
        $this->assertFalse($rule->min('yellow'));
        
        $this->assertFalse($rule->min(new \DateTime(), [45]));
        
        $this->assertFalse($rule->min(null, [45]));
        
        $this->assertFalse($rule->min(true, [true]));        
    }
    
    public function testMaxMethod()
    {
        $rule = new Arr();
        
        $this->assertTrue($rule->max(
            ['red'],
            [1]
        ));
        
        $this->assertTrue($rule->max(
            ['red'],
            ['1']
        ));        
        
        $this->assertFalse($rule->max(
            ['red', 'red', 'yellow'],
            [2]
        ));
        
        $this->assertFalse($rule->max(
            ['red', 'red', 'yellow'],
            ['2']
        ));
        
        $this->assertTrue($rule->max(
            [],
            []
        ));
        
        $this->assertTrue($rule->max(
            ['blue'],
            []
        ));
        
        $this->assertFalse($rule->max('yellow'));
        
        $this->assertFalse($rule->max(new \DateTime(), [45]));
        
        $this->assertFalse($rule->max(null, [45]));
        
        $this->assertFalse($rule->max(true, [true]));         
    }    
}