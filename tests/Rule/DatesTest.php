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
use Tobento\Service\Validation\Rule\Dates;
use Tobento\Service\Validation\RuleInterface;

/**
 * DatesTest
 */
class DatesTest extends TestCase
{    
    public function testThatImplementsRuleInterface()
    {
        $this->assertInstanceOf(
            RuleInterface::class,
            new Dates()
        );
    }
    
    public function testDateMethod()
    {
        $rule = new Dates();
        
        $this->assertTrue($rule->date('2021-02-15 13:05'));
        
        $this->assertTrue($rule->date('2021-02-15'));
        
        $this->assertTrue($rule->date('20.05.2021 15:00'));
        
        $this->assertTrue($rule->date('20.05.2021'));
        
        $this->assertTrue($rule->date('2017-12-24T14:21:44+00:00'));
        
        $this->assertTrue($rule->date('Wed, 02 Oct 19 08:00:00 EST'));
        
        $this->assertFalse($rule->date(true));
        
        $this->assertFalse($rule->date(false));
        
        $this->assertFalse($rule->date(0));
        
        $this->assertFalse($rule->date(1));
        
        $this->assertFalse($rule->date([]));
        
        $this->assertFalse($rule->date(new \DateTime()));
    }
    
    public function testDateFormatMethod()
    {
        $rule = new Dates();
        
        $this->assertTrue(
            $rule->dateFormat('2017-12-24', ['Y-m-d'])
        );        
        
        $this->assertFalse(
            $rule->dateFormat('2017-24-12', ['Y-m-d'])
        );

        $this->assertTrue(
            $rule->dateFormat('13:05:33', ['H:i:s'])
        );
        
        $this->assertFalse(
            $rule->dateFormat('2021-02-15 13:05:33', ['H:i:s'])
        );
        
        $this->assertTrue(
            $rule->dateFormat('2017-12-24T14:21', ['Y-m-d\TH:i'])
        );
        
        $this->assertTrue(
            $rule->dateFormat('2017-12-24T14:21', ['Y-m-d', 'Y-m-d\TH:i'])
        );        
        
        $this->assertFalse($rule->dateFormat(true));
        
        $this->assertFalse($rule->dateFormat(false));
        
        $this->assertFalse($rule->dateFormat(0));
        
        $this->assertFalse($rule->dateFormat(1));
        
        $this->assertFalse($rule->dateFormat([]));
        
        $this->assertFalse($rule->dateFormat(new \DateTime()));
    }
    
    public function testDateBeforeMethod()
    {
        $rule = new Dates();
        
        $this->assertTrue(
            $rule->dateBefore('2017-12-24', ['2017-12-25'])
        );
        
        $this->assertTrue(
            $rule->dateBefore('2017-12-24 14:23', ['2017-12-24 14:24'])
        );
        
        $this->assertFalse(
            $rule->dateBefore('2017-12-24 14:23', ['2017-12-24 14:22'])
        );        
        
        $this->assertFalse(
            $rule->dateBefore('2017-12-24', ['2017-12-24'])
        );
        
        $this->assertTrue(
            $rule->dateBefore('2017-12-24', ['2017-12-24', true])
        );
        
        $this->assertFalse(
            $rule->dateBefore('2017-12-24', ['2017-12-24', '0'])
        );        
        
        $this->assertFalse($rule->dateBefore(true));
        
        $this->assertFalse($rule->dateBefore(false));
        
        $this->assertFalse($rule->dateBefore(0));
        
        $this->assertFalse($rule->dateBefore(1));
        
        $this->assertFalse($rule->dateBefore([]));
        
        $this->assertFalse($rule->dateBefore(new \DateTime()));
    }
    
    public function testDateAfterMethod()
    {
        $rule = new Dates();
        
        $this->assertTrue(
            $rule->dateAfter('2017-12-25', ['2017-12-24'])
        );
        
        $this->assertTrue(
            $rule->dateAfter('2017-12-24 14:24', ['2017-12-24 14:23'])
        );
        
        $this->assertFalse(
            $rule->dateAfter('2017-12-24 14:22', ['2017-12-24 14:23'])
        );        
        
        $this->assertFalse(
            $rule->dateAfter('2017-12-24', ['2017-12-24'])
        );
        
        $this->assertFalse(
            $rule->dateAfter('2017-12-24', ['2017-12-24'], false)
        );        
        
        $this->assertFalse($rule->dateAfter(true));
        
        $this->assertFalse($rule->dateAfter(false));
        
        $this->assertFalse($rule->dateAfter(0));
        
        $this->assertFalse($rule->dateAfter(1));
        
        $this->assertFalse($rule->dateAfter([]));
        
        $this->assertFalse($rule->dateAfter(new \DateTime()));
    }    
}