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
use Tobento\Service\Validation\Rule\Type;
use Tobento\Service\Validation\RuleInterface;

/**
 * TypeTest
 */
class TypeTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Type()
        );
    }
    
    public function testStringMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->string('foo'));
        
        $this->assertTrue($rule->string(''));
        
        $this->assertFalse($rule->string(true));
        
        $this->assertFalse($rule->string(false));
        
        $this->assertFalse($rule->string(0));
        
        $this->assertFalse($rule->string(1));
        
        $this->assertFalse($rule->string([]));
        
        $this->assertFalse($rule->string(new \DateTime()));
    }
    
    public function testIntMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->int(0));
        
        $this->assertTrue($rule->int(23));
        
        $this->assertFalse($rule->int('23'));
        
        $this->assertFalse($rule->int('foo'));
        
        $this->assertFalse($rule->int(''));
        
        $this->assertFalse($rule->int(true));
        
        $this->assertFalse($rule->int(false));
                
        $this->assertFalse($rule->int([]));
        
        $this->assertFalse($rule->int(new \DateTime()));
    }
    
    public function testFloatMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->float(0.0));
        
        $this->assertTrue($rule->float(-0.0));
        
        $this->assertTrue($rule->float(23.000));
        
        $this->assertFalse($rule->float(0));
        
        $this->assertFalse($rule->float(23));  
        
        $this->assertFalse($rule->float('23'));
        
        $this->assertFalse($rule->float('foo'));
        
        $this->assertFalse($rule->float(''));
        
        $this->assertFalse($rule->float(true));
        
        $this->assertFalse($rule->float(false));
                
        $this->assertFalse($rule->float([]));
        
        $this->assertFalse($rule->float(new \DateTime()));
    }
    
    public function testNumericMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->numeric(0.0));
        
        $this->assertTrue($rule->numeric(-0.0));
        
        $this->assertTrue($rule->numeric(23.000));
        
        $this->assertTrue($rule->numeric(0));
        
        $this->assertTrue($rule->numeric(23));  
        
        $this->assertTrue($rule->numeric('23'));
        
        $this->assertFalse($rule->numeric('foo'));
        
        $this->assertFalse($rule->numeric(''));
        
        $this->assertFalse($rule->numeric(true));
        
        $this->assertFalse($rule->numeric(false));
                
        $this->assertFalse($rule->numeric([]));
        
        $this->assertFalse($rule->numeric(new \DateTime()));
    }
    
    public function testScalarMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->scalar(4.0));

        $this->assertTrue($rule->scalar(23));  
        
        $this->assertTrue($rule->scalar('23'));
        
        $this->assertTrue($rule->scalar('foo'));
        
        $this->assertTrue($rule->scalar(''));
        
        $this->assertTrue($rule->scalar(true));
        
        $this->assertTrue($rule->scalar(false));
                
        $this->assertFalse($rule->scalar([]));
        
        $this->assertFalse($rule->scalar(new \DateTime()));
    }
    
    public function testBoolMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->bool(true));
        
        $this->assertTrue($rule->bool(false));
        
        $this->assertTrue($rule->bool('1'));
        
        $this->assertTrue($rule->bool('0'));
        
        $this->assertFalse($rule->bool(4.0));

        $this->assertFalse($rule->bool(23));  
        
        $this->assertFalse($rule->bool('23'));
        
        $this->assertFalse($rule->bool('foo'));
        
        $this->assertFalse($rule->bool(''));
                        
        $this->assertFalse($rule->bool([]));
        
        $this->assertFalse($rule->bool(new \DateTime()));
    }
    
    public function testArrayMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->array([]));
        
        $this->assertTrue($rule->array(['foo', 'bar']));
        
        $this->assertFalse($rule->array(true));
        
        $this->assertFalse($rule->array(4.0));
        
        $this->assertFalse($rule->array('foo'));
        
        $this->assertFalse($rule->array(''));
        
        $this->assertFalse($rule->array(new \DateTime()));
    }
    
    public function testJsonMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->json('{}'));
        
        $this->assertTrue($rule->json('{"name":"John"}'));
        
        $this->assertFalse($rule->json('["name":"John"]'));
        
        $this->assertFalse($rule->json('{"name":"John}'));
        
        $this->assertFalse($rule->json(true));
        
        $this->assertFalse($rule->json(4.0));
        
        $this->assertFalse($rule->json('foo'));
        
        $this->assertFalse($rule->json(''));
        
        $this->assertFalse($rule->json(new \DateTime()));
    }
    
    public function testNotEmptyMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->notEmpty('f'));
        
        $this->assertFalse($rule->notEmpty('0'));
        
        $this->assertTrue($rule->notEmpty(true));
        
        $this->assertFalse($rule->notEmpty(null));
        
        $this->assertFalse($rule->notEmpty(''));
        
        $this->assertTrue($rule->notEmpty(new \DateTime()));
    }
    
    public function testNotNullMethod()
    {
        $rule = new Type();
        
        $this->assertTrue($rule->notNull('f'));
        
        $this->assertTrue($rule->notNull('0'));
        
        $this->assertTrue($rule->notNull(true));
        
        $this->assertFalse($rule->notNull(null));
        
        $this->assertTrue($rule->notNull(''));
        
        $this->assertTrue($rule->notNull(new \DateTime()));
    }    
}