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

use Tobento\Service\Message\ModifierInterface;
use Tobento\Service\Message\MessageInterface;

/**
 * RuleParametersModifier
 */
class RuleParametersModifier implements ModifierInterface
{
    /**
     * Create a new RuleParametersModifier.
     *
     * @param array<int, string> $toMessageParameters
     */    
    public function __construct(
        protected array $toMessageParameters = ['count', 'limit_length'],
    ) {}
    
    /**
     * Returns the modified message.
     *
     * @param MessageInterface The message to midify.
     * @return MessageInterface The mofified message.
     */    
    public function modify(MessageInterface $message): MessageInterface
    {        
        $parameters = $message->parameters();
        
        // add to message parameters
        foreach($message->context() as $key => $value)
        {
            if (is_string($key) && str_starts_with($key, ':')) {
                $parameters[$key] = $value;
                continue;
            }
            
            if (in_array($key, $this->toMessageParameters)) {
                $parameters[$key] = $value;
            }
        }
        
        // assign attribute:    
        if (isset($message->context()[':attribute'])) {
            $parameters[':attribute'] = $message->context()[':attribute'];
        } else {
            $parameters[':attribute'] = $message->key() ?: '';
        }
        
        // assign the value:
        $value = $message->context()['value'] ?? '';
        
        if (is_scalar($value)) {
            $value = (string)$value;
        } else {
            $value = '['.gettype($value).']';
        }
        
        $parameters[':value'] = $value;
        
        // handle parameters:
        // :parameters, :parameters[0], :parameters[1], ...
        $ruleParameters = $message->context()['rule_parameters'] ?? [];
        
        $replace = [];
        
        foreach($ruleParameters as $key => $value)
        {
            $key = ':parameters['.(string)$key.']';
            
            // if params is set as custom key on the rule definition
            // we use this instead.
            if (isset($message->context()[$key])) {
                $replace[$key] = $message->context()[$key];
            } else {
                $replace[$key] = $value;
            }
        }
        
        $ruleParams = $ruleParameters;
        unset($ruleParams[0]);
        
        $replace[':parameters[-1]'] = implode(', ', array_map(function(mixed $item): string {
            return is_scalar($item) ? (string)$item : '';
        }, $ruleParams));
        
        $replace[':parameters'] = implode(', ', array_map(function(mixed $item): string {
            return is_scalar($item) ? (string)$item : '';
        }, $ruleParameters));
        
        $message = $message->withMessage(
            strtr($message->message(), $replace)
        );
        
        return $message->withParameters($parameters);   
    }
}