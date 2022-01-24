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
use Tobento\Service\Validation\Rule\Strings;
use Tobento\Service\Validation\RuleInterface;

/**
 * StringsTest
 */
class StringsTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Strings()
        );
    }
    
    public function testAlphabeticMethod()
    {
        $rule = new Strings();
        
        $this->assertTrue($rule->alphabetic('abc'));
        
        $this->assertTrue($rule->alphabetic('abDFc'));
        
        $this->assertTrue($rule->alphabetic('aübàc'));
        
        $this->assertTrue($rule->alphabetic('aÜbc'));
        
        $this->assertFalse($rule->alphabetic('a bc'));
        
        $this->assertFalse($rule->alphabetic(' abc'));
        
        $this->assertFalse($rule->alphabetic('a<bc'));
        
        $this->assertFalse($rule->alphabetic('123'));
        
        $this->assertFalse($rule->alphabetic(''));
        
        $this->assertFalse($rule->alphabetic(' '));
        
        $this->assertFalse($rule->alphabetic(true));
        
        $this->assertFalse($rule->alphabetic(false));
        
        $this->assertFalse($rule->alphabetic(0));
        
        $this->assertFalse($rule->alphabetic(1));
        
        $this->assertFalse($rule->alphabetic([]));
        
        $this->assertFalse($rule->alphabetic(new \DateTime()));
    }
    
    public function testAlphaMethod()
    {
        $rule = new Strings();
        
        $this->assertTrue($rule->alpha('abc'));
        
        $this->assertTrue($rule->alpha('abDFc'));
        
        $this->assertFalse($rule->alpha('aübàc'));
        
        $this->assertFalse($rule->alpha('aÜbc'));
        
        $this->assertFalse($rule->alpha('a bc'));
        
        $this->assertFalse($rule->alpha(' abc'));
        
        $this->assertFalse($rule->alpha('a<bc'));
        
        $this->assertFalse($rule->alpha('123'));
        
        $this->assertFalse($rule->alpha(''));
        
        $this->assertFalse($rule->alpha(' '));
        
        $this->assertFalse($rule->alpha(true));
        
        $this->assertFalse($rule->alpha(false));
        
        $this->assertFalse($rule->alpha(0));
        
        $this->assertFalse($rule->alpha(1));
        
        $this->assertFalse($rule->alpha([]));
        
        $this->assertFalse($rule->alpha(new \DateTime()));
    }
    
    public function testAlphabeticNumMethod()
    {
        $rule = new Strings();
        
        $this->assertTrue($rule->alphabeticNum('abc'));
        
        $this->assertTrue($rule->alphabeticNum('abDFc'));
        
        $this->assertTrue($rule->alphabeticNum('aübàc'));
        
        $this->assertTrue($rule->alphabeticNum('aÜbc'));
        
        $this->assertTrue($rule->alphabeticNum('2abc'));
        
        $this->assertTrue($rule->alphabeticNum('abDFc3'));
        
        $this->assertTrue($rule->alphabeticNum('aübà4c'));
        
        $this->assertTrue($rule->alphabeticNum('364364'));
        
        $this->assertTrue($rule->alphabeticNum(677));
        
        $this->assertTrue($rule->alphabeticNum(0));
        
        $this->assertTrue($rule->alphabeticNum(1));
        
        $this->assertFalse($rule->alphabeticNum(-1));
        
        $this->assertFalse($rule->alphabeticNum(67.7));
        
        $this->assertFalse($rule->alphabeticNum('a5 bc'));
        
        $this->assertFalse($rule->alphabeticNum(' 677'));
                
        $this->assertFalse($rule->alphabeticNum('a<b4c'));
        
        $this->assertFalse($rule->alphabeticNum(''));
        
        $this->assertFalse($rule->alphabeticNum(' '));
        
        $this->assertFalse($rule->alphabeticNum(true));
        
        $this->assertFalse($rule->alphabeticNum(false));
                
        $this->assertFalse($rule->alphabeticNum([]));
        
        $this->assertFalse($rule->alphabeticNum(new \DateTime()));
    }
    
    public function testAlnumMethod()
    {
        $rule = new Strings();
        
        $this->assertTrue($rule->alnum('abc'));
        
        $this->assertTrue($rule->alnum('abDFc'));
        
        $this->assertFalse($rule->alnum('aübàc'));
        
        $this->assertFalse($rule->alnum('aÜbc'));
        
        $this->assertTrue($rule->alnum('2abc'));
        
        $this->assertTrue($rule->alnum('abDFc3'));
        
        $this->assertFalse($rule->alnum('aübà4c'));
        
        $this->assertTrue($rule->alnum('364364'));
        
        $this->assertTrue($rule->alnum(677));
        
        $this->assertFalse($rule->alnum(5));
        
        $this->assertFalse($rule->alnum(67.7));
        
        $this->assertFalse($rule->alnum('a5 bc'));
        
        $this->assertFalse($rule->alnum(' 677'));
                
        $this->assertFalse($rule->alnum('a<b4c'));
        
        $this->assertFalse($rule->alnum(''));
        
        $this->assertFalse($rule->alnum(' '));
        
        $this->assertFalse($rule->alnum(true));
        
        $this->assertFalse($rule->alnum(false));
                
        $this->assertFalse($rule->alnum([]));
        
        $this->assertFalse($rule->alnum(new \DateTime()));
    }    
}