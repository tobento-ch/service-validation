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
 * DefaultRulesAutowiringTest
 */
class DefaultRulesAutowiringTest extends TestCase
{
    public function testAddMethodWithObject()
    {        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
        
        $rule = new Rule\Same();
        
        $rules->add('same', $rule);
        
        $this->assertTrue($rule === $rules->get('same'));
    }
    
    public function testAddMethodLazy()
    {        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('same', Rule\Same::class);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('same')
        );
    }
    
    public function testAddMethodCustomMethod()
    {        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('bool', [new Rule\Type(), 'bool']);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('bool')
        );
    }
    
    public function testAddMethodCustomMethodLazy()
    {        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('bool', [Rule\Type::class, 'bool']);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('bool')
        );
    }

    public function testAddMethodUnresolvableThrowsInvalidRuleException()
    {
        $this->expectException(InvalidRuleException::class);
        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('rule', UnresolvableParameterRule::class);

        $rules->get('rule');
    }
    
    public function testAddMethodUnresolvable()
    {
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('rule', [UnresolvableParameterRule::class, 'passes', ['name' => 'value']]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
    
    public function testAddMethodUnresolvableWithoutMethod()
    {
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('rule', [UnresolvableParameterRule::class, ['name' => 'value']]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
    
    public function testAddMethodResolvable()
    {        
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('rule', ResolvableParameterRule::class);
        
        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }  
    
    public function testAddMethodResolvableWithParameterSet()
    {
        $rules = new DefaultRules(
            ruleFactory: new AutowiringRuleFactory(new Container())
        );
                
        $rules->add('rule', [ResolvableParameterRule::class, ['foo' => new Foo()]]);

        $this->assertInstanceOf(
            RuleInterface::class,
            $rules->get('rule')
        );
    }
}