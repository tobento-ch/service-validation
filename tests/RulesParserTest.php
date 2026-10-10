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
use Tobento\Service\Validation\RulesParser;
use Tobento\Service\Validation\RulesParserInterface;
use Tobento\Service\Validation\RulesParserException;
use Tobento\Service\Validation\ParsedRule;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Test\Mock\ResolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\UnresolvableParameterRule;
use Tobento\Service\Validation\Test\Mock\Foo;

/**
 * RulesParserTest
 */
class RulesParserTest extends TestCase
{    
    public function testThatImplementsRulesParserInterface()
    {
        $this->assertInstanceOf(
            RulesParserInterface::class,
            new RulesParser()
        );
    }

    public function testThrowsRulesParserException()
    {
        $this->expectException(RulesParserException::class);
        
        $parser = new RulesParser();
        
        $parser->parse([[]]);
    }

    public function testEmptyString()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse('');
        
        $this->assertSame(
            [],
            $parsed
        );
    }
    
    public function testStringRule()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse('alpha');
        
        $this->assertSame(
            'alpha',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testStringRuleWithParams()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse('in:red:blue:green');
        
        $this->assertSame(
            'in',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['red', 'blue', 'green'],
            ],
            $parsed[0]->parameters()
        );
    }
    
    public function testStringRules()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse('alpha|in:red:blue:green');

        $this->assertSame(
            'alpha',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
        
        $this->assertSame(
            'in',
            $parsed[1]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['red', 'blue', 'green'],
            ],
            $parsed[1]->parameters()
        );
    }
    
    public function testStringRulesParameters()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse('in:red:blue:green|minLen:2');
        
        $this->assertSame(
            'in',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['red', 'blue', 'green'],
            ],
            $parsed[0]->parameters()
        );
        
        $this->assertSame(
            'minLen',
            $parsed[1]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['2'],
            ],
            $parsed[1]->parameters()
        );        
    }    
    
    public function testArrayWithStringRule()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            ['alpha'],
        ]);
        
        $this->assertSame(
            'alpha',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithStringRuleWithParams()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            ['in:red:blue:green'],
        ]);
        
        $this->assertSame(
            'in',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['red', 'blue', 'green'],
            ],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithStringRuleUsesParamsAsArrayInstead()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            ['in:red:blue:green', ['red', 'blue']],
        ]);
        
        $this->assertSame(
            'in',
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [
                'rule_parameters' => ['red', 'blue'],
            ],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithObjectRule()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            $rule,
        ]);
        
        $this->assertSame(
            $rule,
            $parsed[0]->rule()
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayObjectRule()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            [$rule],
        ]);
        
        $this->assertSame(
            $rule,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'passes',
            $parsed[0]->rule()[1]
        );        
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayObjectRuleWithParams()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            [$rule, ['red']],
        ]);
        
        $this->assertSame(
            $rule,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'passes',
            $parsed[0]->rule()[1]
        );        
        
        $this->assertSame(
            [
                'rule_parameters' => ['red'],
            ],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayStringClassRule()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            [[Rule\Same::class]],
        ]);
        
        $this->assertSame(
            Rule\Same::class,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'passes',
            $parsed[0]->rule()[1]
        );        
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayStringClassRuleWithParams()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            [[Rule\Same::class], ['red']],
        ]);
        
        $this->assertSame(
            Rule\Same::class,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'passes',
            $parsed[0]->rule()[1]
        );        
        
        $this->assertSame(
            [
                'rule_parameters' => ['red'],
            ],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayStringClassRuleWithMethod()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            [[Rule\Same::class, 'method']],
        ]);
        
        $this->assertSame(
            Rule\Same::class,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'method',
            $parsed[0]->rule()[1]
        );        
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayStringClassRuleWithClassParams()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            [[Rule\Same::class, ['name' => 'value']]],
        ]);
        
        $this->assertSame(
            Rule\Same::class,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'passes',
            $parsed[0]->rule()[1]
        );        

        $this->assertSame(
            ['name' => 'value'],
            $parsed[0]->rule()[2]
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayStringClassRuleWithClassParamsAndMethod()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            [[Rule\Same::class, 'method', ['name' => 'value']]],
        ]);
        
        $this->assertSame(
            Rule\Same::class,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'method',
            $parsed[0]->rule()[1]
        );        

        $this->assertSame(
            ['name' => 'value'],
            $parsed[0]->rule()[2]
        );
        
        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayWithArrayObjectRuleWithMethod()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            [[$rule, 'method']],
        ]);
        
        $this->assertSame(
            $rule,
            $parsed[0]->rule()[0]
        );
        
        $this->assertSame(
            'method',
            $parsed[0]->rule()[1]
        );        

        $this->assertSame(
            [],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayStringRuleKeepsParams()
    {        
        $parser = new RulesParser();
        
        $parsed = $parser->parse([
            'limit_length' => 100,
            'error' => 'global message',
            ['alpha', 'error' => 'message'],
        ]);  

        $this->assertSame(
            ['limit_length' => 100, 'error' => 'message'],
            $parsed[0]->parameters()
        );
    }
    
    public function testObjectRuleKeepsParams()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            'limit_length' => 100,
            'error' => 'global message',
            [new Rule\Same(), 'error' => 'message'],
        ]);  

        $this->assertSame(
            ['limit_length' => 100, 'error' => 'message'],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayStringClassRuleKeepsParams()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            'limit_length' => 100,
            'error' => 'global message',
            [[Rule\Same::class], 'error' => 'message'],
        ]);  

        $this->assertSame(
            ['limit_length' => 100, 'error' => 'message'],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayObjectRuleKeepsParams()
    {        
        $parser = new RulesParser();
        
        $rule = new Rule\Same();
        
        $parsed = $parser->parse([
            'limit_length' => 100,
            'error' => 'global message',
            [[new Rule\Same()], 'error' => 'message'],
        ]);  

        $this->assertSame(
            ['limit_length' => 100, 'error' => 'message'],
            $parsed[0]->parameters()
        );
    }
    
    public function testArrayRuleErrorDoesNotLeakIntoFollowingStringRule()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            ['alpha', 'error' => 'message'],
            'minLen:2',
        ]);

        $this->assertSame(
            ['error' => 'message'],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            ['rule_parameters' => ['2']],
            $parsed[1]->parameters()
        );
    }

    public function testArrayRuleParametersDoNotLeakIntoFollowingStringRule()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            ['minLen', [2]],
            'minLen:100',
        ]);

        $this->assertSame(
            ['rule_parameters' => [2]],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            ['rule_parameters' => ['100']],
            $parsed[1]->parameters()
        );
    }

    public function testArrayRuleParametersDoNotLeakIntoFollowingStringRuleWithoutParameters()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            ['alpha', [3]],
            'alpha',
        ]);

        $this->assertSame(
            ['rule_parameters' => [3]],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            [],
            $parsed[1]->parameters()
        );
    }

    public function testArrayRuleParametersDoNotLeakIntoFollowingArrayRule()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            ['minLen', [2], 'error' => 'message'],
            ['alpha'],
        ]);

        $this->assertSame(
            ['rule_parameters' => [2], 'error' => 'message'],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            [],
            $parsed[1]->parameters()
        );
    }

    public function testArrayObjectRuleParametersDoNotLeakIntoFollowingRule()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            [new Rule\Same(), [5], 'error' => 'message'],
            'alpha',
        ]);

        $this->assertSame(
            ['rule_parameters' => [5], 'error' => 'message'],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            [],
            $parsed[1]->parameters()
        );
    }

    public function testArrayStringClassRuleParametersDoNotLeakIntoFollowingRule()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            [[Rule\Same::class], [5], 'error' => 'message'],
            'alpha',
        ]);

        $this->assertSame(
            ['rule_parameters' => [5], 'error' => 'message'],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            [],
            $parsed[1]->parameters()
        );
    }

    public function testArrayRuleOverridesGlobalParametersOnlyForItself()
    {
        $parser = new RulesParser();

        $parsed = $parser->parse([
            'error' => 'global message',
            ['alpha', 'error' => 'message'],
            'minLen:2',
            ['minLen', [3]],
        ]);

        $this->assertSame(
            ['error' => 'message'],
            $parsed[0]->parameters()
        );

        $this->assertSame(
            ['error' => 'global message', 'rule_parameters' => ['2']],
            $parsed[1]->parameters()
        );

        $this->assertSame(
            ['error' => 'global message', 'rule_parameters' => [3]],
            $parsed[2]->parameters()
        );
    }
}