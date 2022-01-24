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
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\RulesAware;
use Tobento\Service\Validation\DefaultRules;
use Tobento\Service\Validation\RulesParser;
use Tobento\Service\Validation\Message\MessagesFactory;

/**
 * ValidatorTest
 */
class ValidatorTest extends TestCase
{    
    public function testThatImplementsValidatorInterface()
    {
        $this->assertInstanceOf(
            ValidatorInterface::class,
            new Validator()
        );
    }
    
    public function testThatImplementsRulesAware()
    {
        $this->assertInstanceOf(
            RulesAware::class,
            new Validator()
        );
    }
    
    public function testConstruct()
    {
        $validator = new Validator(
            rules: new DefaultRules(),
            rulesParser: new RulesParser(),
            messagesFactory: new MessagesFactory()
        );
        
        $this->assertInstanceOf(
            ValidatorInterface::class,
            new Validator()
        );        
    }    
}