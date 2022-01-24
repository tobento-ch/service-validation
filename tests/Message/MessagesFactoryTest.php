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

namespace Tobento\Service\Validation\Test\Message;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Validation\Message\RuleParametersModifier;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\Modifier\ParameterReplacer;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

/**
 * MessagesFactoryTest
 */
class MessagesFactoryTest extends TestCase
{    
    public function testThatImplementsMessagesFactoryInterface()
    {
        $this->assertInstanceOf(
            MessagesFactoryInterface::class,
            new MessagesFactory()
        );
    }
    
    public function testConstruct()
    {
        $logger = new Logger('name');
        $testHandler = new TestHandler();
        $logger->pushHandler($testHandler);
        
        $messagesFactory = new MessagesFactory(
            messageFactory: new MessageFactory(),
            modifiers: new Modifiers(),
            logger: $logger,
        );
        
        $this->assertInstanceOf(
            MessagesFactoryInterface::class,
            $messagesFactory
        );        
    }

    public function testDefaultModifiersIfNoneAreSet()
    {        
        $messagesFactory = new MessagesFactory();
        
        $this->assertInstanceOf(
            RuleParametersModifier::class,
            $messagesFactory->modifiers()->all()[0]
        );
        
        $this->assertInstanceOf(
            ParameterReplacer::class,
            $messagesFactory->modifiers()->all()[1]
        );
        
        $this->assertSame(
            2,
            count($messagesFactory->modifiers()->all())
        );        
    }
    
    public function testModifiersMethod()
    {
        $modifiers = new Modifiers();
        
        $messagesFactory = new MessagesFactory(
            modifiers: $modifiers,
        );
        
        $this->assertSame(
            $modifiers,
            $messagesFactory->modifiers()
        );        
    }
    
    public function testCreateMessagesMethod()
    {        
        $messagesFactory = new MessagesFactory();
        
        $this->assertInstanceOf(
            MessagesInterface::class,
            $messagesFactory->createMessages()
        );     
    }    
}