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

namespace Tobento\Service\Validation\Message;

use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\MessageFactory;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Message\Messages;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\Modifier\ParameterReplacer;
use Psr\Log\LoggerInterface;

/**
 * MessagesFactory
 */
class MessagesFactory implements MessagesFactoryInterface
{
    /**
     * Create a new MessagesFactory.
     *
     * @param null|MessageFactoryInterface $messageFactory
     * @param null|ModifiersInterface $modifiers
     * @param null|LoggerInterface $logger
     */    
    public function __construct(
        protected null|MessageFactoryInterface $messageFactory = null,
        protected null|ModifiersInterface $modifiers = null,
        protected null|LoggerInterface $logger = null,
    ) {
        $this->messageFactory = $messageFactory ?: new MessageFactory();
        
        if (is_null($modifiers)) {
            $this->modifiers = new Modifiers(
                new RuleParametersModifier(),
                new ParameterReplacer(),
            );
        }
    }

    /**
     * Returns the modifiers.
     *
     * @return ModifiersInterface
     */
    public function modifiers(): ModifiersInterface
    {
        if (is_null($this->modifiers)) {
            $this->modifiers = new Modifiers();
        }
        
        return $this->modifiers;
    }
    
    /**
     * Create a new Messages.
     *
     * @return MessagesInterface
     */
    public function createMessages(): MessagesInterface
    {
        return new Messages(
            $this->messageFactory,
            $this->modifiers,
            $this->logger,
        );
    }
}