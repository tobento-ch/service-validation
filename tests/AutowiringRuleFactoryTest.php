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
use Tobento\Service\Validation\AutowiringRuleFactory;
use Tobento\Service\Container\Container;
use Tobento\Service\Validation\RuleFactoryInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\InvalidRuleException;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\Test\Mock\ResolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\UnresolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\Foo;

/**
 * AutowiringRuleFactoryTest
 */
class AutowiringRuleFactoryTest extends TestCase
{    
    public function testThatImplementsRuleFactoryInterface()
    {
        $this->assertInstanceOf(
            RuleFactoryInterface::class,
            new AutowiringRuleFactory(new Container())
        );
    }
    
    public function testThrowsInvalidRuleException()
    {
        $this->expectException(InvalidRuleException::class);
        
        $factory = new AutowiringRuleFactory(new Container());
        
        $factory->createRule(Rule\NotExist::class);
    }
    
    public function testObjectRule()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule(new Rule\Same())
        );
    }
    
    public function testStringRule()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule(Rule\Same::class)
        );
    }
    
    public function testStringRuleResolvable()
    {        
        $factory = new AutowiringRuleFactory(new Container());

        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule(ResolvableParameterRule::class)
        );
    }    

    public function testArrayWithObjectRule()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule([new Rule\Same(), 'passes'])
        );
    }
    
    public function testArrayWithStringRule()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule([Rule\Same::class, 'passes'])
        );
    }
    
    public function testArrayWithStringRuleAndParameters()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule([UnresolvableParameterRule::class, 'passes', ['name' => 'value']])
        );
    }
    
    public function testArrayWithStringRuleAndParametersOnly()
    {
        $factory = new AutowiringRuleFactory(new Container());
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $factory->createRule([UnresolvableParameterRule::class, ['name' => 'value']])
        );
    }     
}