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
use Tobento\Service\Validation\Rule\Address;
use Tobento\Service\Validation\RuleInterface;

/**
 * AddressTest
 */
class AddressTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Address()
        );
    }
    
    public function testEmailMethod()
    {
        $rule = new Address();
        
        $this->assertTrue($rule->email('test@email.com'));
        
        $this->assertTrue($rule->email('hans.peter@email.com'));
        
        $this->assertTrue($rule->email('häns.peter@email.com'));
        
        $this->assertTrue($rule->email('häns.peter@emailä.com'));
        
        $this->assertTrue($rule->email('hans.peter@example-email.com'));
        
        $this->assertFalse($rule->email('foo'));
        
        $this->assertFalse($rule->email('foo@'));
        
        $this->assertFalse($rule->email(true));
        
        $this->assertFalse($rule->email(false));
        
        $this->assertFalse($rule->email(0));
        
        $this->assertFalse($rule->email(1));
        
        $this->assertFalse($rule->email([]));
        
        $this->assertFalse($rule->email(new \DateTime()));
    }
    
    public function testUrlMethod()
    {
        $rule = new Address();
        
        $this->assertTrue($rule->url('http://example.com'));
        
        $this->assertTrue($rule->url('https://example.com'));
        
        $this->assertTrue($rule->url('https://example.com/'));
        
        $this->assertTrue($rule->url('https://example.com/foo-bar'));
        
        $this->assertTrue($rule->url('https://example.com/foo/bar'));
        
        $this->assertTrue($rule->url('https://example.com/foo?bar=[]'));
        
        $this->assertFalse($rule->url('https://exämple.com'));
        
        $this->assertFalse($rule->url('example.com'));
        
        $this->assertFalse($rule->url('example.com/foo'));
        
        $this->assertFalse($rule->url('//example.com'));
        
        $this->assertFalse($rule->url(true));
        
        $this->assertFalse($rule->url(false));
        
        $this->assertFalse($rule->url(0));
        
        $this->assertFalse($rule->url(1));
        
        $this->assertFalse($rule->url([]));
        
        $this->assertFalse($rule->url(new \DateTime()));
    }
    
    public function testUriMethod()
    {
        $rule = new Address();
        
        $this->assertTrue($rule->uri('http://example.com'));
        
        $this->assertTrue($rule->uri('https://example.com'));
        
        $this->assertTrue($rule->uri('https://example.com/'));
        
        $this->assertTrue($rule->uri('https://example.com/foo-bar'));
        
        $this->assertTrue($rule->uri('https://example.com/foo/bar'));
        
        $this->assertTrue($rule->uri('https://example.com/foo?bar=[]'));
        
        $this->assertFalse($rule->uri('https://exämple.com'));
        
        $this->assertTrue($rule->uri('example.com'));
        
        $this->assertTrue($rule->uri('example.com/foo'));
        
        $this->assertTrue($rule->uri('f'));
        
        $this->assertTrue($rule->uri('foo/bar'));
        
        $this->assertTrue($rule->uri('/foo/bar'));
        
        $this->assertTrue($rule->uri('ba.jpg'));
        
        $this->assertTrue($rule->uri('/foo/bar.html'));
        
        $this->assertTrue($rule->uri('foo-bar/Baz-45'));
        
        $this->assertTrue($rule->uri('//example.com'));
        
        $this->assertFalse($rule->uri('foo bar'));
        
        $this->assertFalse($rule->uri(true));
        
        $this->assertFalse($rule->uri(false));
        
        $this->assertFalse($rule->uri(0));
        
        $this->assertFalse($rule->uri(1));
        
        $this->assertFalse($rule->uri([]));
        
        $this->assertFalse($rule->uri(new \DateTime()));
    }
}