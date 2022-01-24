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
use Tobento\Service\Validation\DefaultRules;
use Tobento\Service\Validation\RulesInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\AutowiringRuleFactory;
use Tobento\Service\Container\Container;
use Tobento\Service\Validation\RuleNotFoundException;
use Tobento\Service\Validation\InvalidRuleException;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\Test\Mock\ResolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\UnresolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\Foo;

/**
 * DefaultRulesTest
 */
class DefaultRulesTest extends TestCase
{    
    public function testThatImplementsRulesInterface()
    {
        $this->assertInstanceOf(
            RulesInterface::class,
            new DefaultRules()
        );
    }

    public function testThrowsRuleNotFoundException()
    {
        $this->expectException(RuleNotFoundException::class);
        
        $rules = new DefaultRules();
        
        $rules->get('unknown');
    }
    
    public function testThrowsInvalidRuleException()
    {
        $this->expectException(InvalidRuleException::class);
        
        $rules = new DefaultRules();
        
        $rules->add('invalid', Rule\NotExist::class);
        
        $rules->get('invalid');
    }    
    
    public function testAddMethodWithObject()
    {        
        $rules = new DefaultRules();
        
        $rule = new Rule\Same();
        
        $rules->add('same', $rule);
        
        $this->assertTrue($rule === $rules->get('same'));
    }
    
    public function testAddMethodLazy()
    {        
        $rules = new DefaultRules();
                
        $rules->add('same', Rule\Same::class);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('same')
        );
    }
    
    public function testAddMethodCustomMethod()
    {        
        $rules = new DefaultRules();
                
        $rules->add('bool', [new Rule\Type(), 'bool']);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('bool')
        );
    }
    
    public function testAddMethodCustomMethodLazy()
    {        
        $rules = new DefaultRules();
                
        $rules->add('bool', [Rule\Type::class, 'bool']);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('bool')
        );
    }

    public function testAddMethodUnresolvableThrowsInvalidRuleException()
    {
        $this->expectException(InvalidRuleException::class);
        
        $rules = new DefaultRules();
                
        $rules->add('rule', UnresolvableParameterRule::class);

        $rules->get('rule');
    }
    
    public function testAddMethodUnresolvable()
    {
        $rules = new DefaultRules();
                
        $rules->add('rule', [UnresolvableParameterRule::class, 'passes', ['name' => 'value']]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
    
    public function testAddMethodUnresolvableWithoutMethod()
    {
        $rules = new DefaultRules();
                
        $rules->add('rule', [UnresolvableParameterRule::class, ['name' => 'value']]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
    
    public function testAddMethodResolvableThrowsInvalidRuleException()
    {
        $this->expectException(InvalidRuleException::class);
        
        $rules = new DefaultRules();
                
        $rules->add('rule', ResolvableParameterRule::class);

        $rules->get('rule');
    }  
    
    public function testAddMethodResolvableWithParameterSet()
    {
        $rules = new DefaultRules();
                
        $rules->add('rule', [ResolvableParameterRule::class, ['foo' => new Foo()]]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
}