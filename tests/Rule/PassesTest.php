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
use Tobento\Service\Validation\Rule\Passes;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\Rule\AutowireAware;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Validation\Test\Mock\Foo;
use Tobento\Service\Autowire\Autowire;
use Tobento\Service\Container\Container;
use InvalidArgumentException;

/**
 * PassesTest
 */
class PassesTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(RuleInterface::class, new Passes(true));
        $this->assertInstanceOf(AutowireAware::class, new Passes(true));
    }
    
    public function testPassesWithBoolTrueDoesPass()
    {
        $rule = new Passes(passes: true);
        
        $this->assertTrue($rule->passes('value'));
    }
    
    public function testPassesWithBoolFalseDoesNotPass()
    {
        $rule = new Passes(passes: false);
        
        $this->assertFalse($rule->passes('value'));
    }
    
    public function testPassesWithCallable()
    {
        $rule = new Passes(passes: function(
            mixed $value,
            array $parameters,
            ValidatorInterface $validator,
            ValidationInterface $validation): bool
        {
            return true;
        });
        
        $this->assertTrue($rule->passes('value'));
    }
    
    public function testPassesWithCallableAutowired()
    {
        $rule = new Passes(passes: function(mixed $value, Foo $foo): bool {
            return true;
        });
        
        $rule->setAutowire(new Autowire(new Container()));
        
        $this->assertTrue($rule->passes('value'));
    }
    
    public function testPassesWithCallableDoesNotPassIfNoBoolReturned()
    {
        $rule = new Passes(passes: function(mixed $value) {
            return false;
        });
        
        $this->assertFalse($rule->passes('value'));
    }
    
    public function testPassesWithCallableAutowiredDoesNotPassIfNoBoolReturned()
    {
        $rule = new Passes(passes: function(mixed $value, Foo $foo) {
            return 'foo';
        });
        
        $rule->setAutowire(new Autowire(new Container()));
        
        $this->assertFalse($rule->passes('value'));
    }
    
    public function testPassesWithClosureVerifiesDeclaredType()
    {
        // mixed:
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes('value'));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes(555));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes(1.5));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes([]));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes(true));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes(null));
        $this->assertTrue((new Passes(passes: fn (mixed $value) => true))->passes(new Foo()));
        
        // string:
        $this->assertTrue((new Passes(passes: fn (string $value) => true))->passes('value'));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes(555));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes(1.5));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes([]));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes(null));
        $this->assertFalse((new Passes(passes: fn (string $value) => true))->passes(new Foo()));
        
        // int:
        $this->assertFalse((new Passes(passes: fn (int $value) => true))->passes('value'));
        $this->assertTrue((new Passes(passes: fn (int $value) => true))->passes(555));
        $this->assertTrue((new Passes(passes: fn (int $value) => true))->passes(1.5));
        $this->assertFalse((new Passes(passes: fn (int $value) => true))->passes([]));
        $this->assertFalse((new Passes(passes: fn (int $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (int $value) => true))->passes(null));
        $this->assertFalse((new Passes(passes: fn (int $value) => true))->passes(new Foo()));
        
        // float:
        $this->assertFalse((new Passes(passes: fn (float $value) => true))->passes('value'));
        $this->assertTrue((new Passes(passes: fn (float $value) => true))->passes(555));
        $this->assertTrue((new Passes(passes: fn (float $value) => true))->passes(1.5));
        $this->assertFalse((new Passes(passes: fn (float $value) => true))->passes([]));
        $this->assertFalse((new Passes(passes: fn (float $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (float $value) => true))->passes(null));
        $this->assertFalse((new Passes(passes: fn (float $value) => true))->passes(new Foo()));
        
        // array:
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes('value'));
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes(555));
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes(1.5));
        $this->assertTrue((new Passes(passes: fn (array $value) => true))->passes([]));
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes(null));
        $this->assertFalse((new Passes(passes: fn (array $value) => true))->passes(new Foo()));
        
        // bool:
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes('value'));
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes(555));
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes(1.5));
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes([]));
        $this->assertTrue((new Passes(passes: fn (bool $value) => true))->passes(true));
        $this->assertTrue((new Passes(passes: fn (bool $value) => true))->passes(false));
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes(null));
        $this->assertFalse((new Passes(passes: fn (bool $value) => true))->passes(new Foo()));
        
        // null:
        $this->assertTrue((new Passes(passes: fn (null|string $value) => true))->passes('value'));
        $this->assertFalse((new Passes(passes: fn (null|string $value) => true))->passes(555));
        $this->assertTrue((new Passes(passes: fn (null|string $value) => true))->passes(null));
        
        // object:
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes('value'));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes(555));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes(1.5));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes([]));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes(false));
        $this->assertFalse((new Passes(passes: fn (object $value) => true))->passes(null));
        $this->assertTrue((new Passes(passes: fn (object $value) => true))->passes(new Foo()));
        
        // union types:
        $this->assertTrue((new Passes(passes: fn (int|string $value) => true))->passes('value'));
        $this->assertTrue((new Passes(passes: fn (int|string $value) => true))->passes(55));
        $this->assertFalse((new Passes(passes: fn (int|string $value) => true))->passes(true));
        $this->assertFalse((new Passes(passes: fn (int|string $value) => true))->passes(null));
    }
    
    public function testPassesWithClosureDoesNotVerifyDeclaredTypeIfDisabled()
    {
        $passes = new Passes(
            passes: fn (string $value) => true,
            verifyDeclaredType: false,
        );
        
        try {
            $passes->passes([]);
        } catch (\TypeError $e) {
            $this->assertTrue(true);
        }
    }
    
    public function testPassesThrowsInvalidArgumentExceptionIfNotBoolOrCallable()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $rule = new Passes(passes: 'foo');
    }
    
    public function testSkipValidationWithBoolTrueDoesSkip()
    {
        $rule = new Passes(passes: false, skipValidation: true);
        
        $this->assertTrue($rule->skipValidation('value'));
    }
    
    public function testSkipValidationWithBoolFalseDoesNotSkip()
    {
        $rule = new Passes(passes: false, skipValidation: false);
        
        $this->assertFalse($rule->skipValidation('value'));
    }
    
    public function testSkipValidationWithCallable()
    {
        $rule = new Passes(passes: false, skipValidation: function(mixed $value): bool {
            return true;
        });
        
        $this->assertTrue($rule->skipValidation('value'));
    }
    
    public function testSkipValidationWithCallableAutowired()
    {
        $rule = new Passes(passes: false, skipValidation: function(mixed $value, Foo $foo): bool {
            return true;
        });
        
        $rule->setAutowire(new Autowire(new Container()));
        
        $this->assertTrue($rule->skipValidation('value'));
    }
    
    public function testSkipValidationWithCallableDoesNotSkipIfNoBoolReturned()
    {
        $rule = new Passes(passes: false, skipValidation: function(mixed $value) {
            return 'foo';
        });
        
        $this->assertFalse($rule->skipValidation('value'));
    }
    
    public function testSkipValidationWithCallableAutowiredDoesNotSkipIfNoBoolReturned()
    {
        $rule = new Passes(passes: false, skipValidation: function(mixed $value, Foo $foo) {
            return 'foo';
        });
        
        $rule->setAutowire(new Autowire(new Container()));
        
        $this->assertFalse($rule->skipValidation('value'));
    }
    
    public function testMessagesMethodWithErrorMessage()
    {        
        $rule = new Passes(passes: true, errorMessage: 'Message');
        
        $this->assertSame('Message', $rule->messages()['passes'] ?? null);
    }
}