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

namespace Tobento\Service\Validation\Html;

use Tobento\Service\Message\Message;
use Tobento\Service\Message\Modifier;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\ModifiersInterface;
use Tobento\Service\Validation\Message\RuleParametersModifier;
use Tobento\Service\Validation\ParsedRule;
use Tobento\Service\Validation\Rule;
use Tobento\Service\Validation\RuleException;
use Tobento\Service\Validation\RulesParser;
use Tobento\Service\Validation\RulesParserInterface;

/**
 * HtmlAttributesFactory
 */
class HtmlAttributesFactory implements HtmlAttributesFactoryInterface
{
    /**
     * @var RulesParserInterface
     */
    private RulesParserInterface $rulesParser;
    
    /**
     * @var ModifiersInterface
     */
    private ModifiersInterface $modifiers;
    
    /**
     * Create a new HtmlAttributesFactory instance.
     *
     * @param null|RulesParserInterface $rulesParser
     * @param null|ModifiersInterface $modifiers
     */
    public function __construct(
        null|RulesParserInterface $rulesParser = null,
        null|ModifiersInterface $modifiers = null,
    ) {
        $this->rulesParser = $rulesParser ?: new RulesParser();
        
        $this->modifiers = new Modifiers(
            new RuleParametersModifier(),
            new Modifier\ParameterReplacer(),
        );
    }

    /**
     * Create attributes for the given rules.
     *
     * @param string|array $rules
     * @param string $inputType
     * @param string $inputName
     * @param null|string $messageDataAttributeName
     * @return array
     */
    public function createAttributes(
        string|array $rules,
        string $inputType,
        string $inputName,
        null|string $messageDataAttributeName = 'data-validation-messages'
    ): array {
        if (is_string($messageDataAttributeName)) {
            $messageDataAttributeName = strtolower($messageDataAttributeName);
            
            if (!str_starts_with($messageDataAttributeName, 'data-')) {
                throw new \InvalidArgumentException('The messageDataAttributeName parameter must start with data-');
            }
        }
        
        $parsedRules = $this->rulesParser->parse($rules);
        $attributes = [];
        
        foreach($parsedRules as $parsedRule) {
            if (! $parsedRule instanceof ParsedRule) {
                continue;
            }
            
            $attribute = $this->ruleToValidationAttribute($parsedRule, $inputType);
            
            if (!is_array($attribute)) {
                continue;
            }
            
            if (! $this->isAllowedForInputType($attribute, $inputType)) {
                continue;
            }
            
            [$name, $value] = $attribute;
            
            if (is_null($value)) {
                if (in_array($name, $attributes)) {
                    continue;
                }
                $attributes[] = $name;
            } else {
                $attributes[$name] = (string)$value;
            }
            
            if (empty($messageDataAttributeName)) {
                continue;
            }
            
            $message = $this->message($parsedRule, $inputName);

            if ($message !== '') {
                $attributes[$messageDataAttributeName][$name] = $message;
            }
        }

        return $attributes;
    }
    
    /**
     * Maps the given parsed rule to the html validation attribute for the given input type.
     * 
     * @param ParsedRule $rule
     * @param string $inputType
     * @return null|array
     */
    protected function ruleToValidationAttribute(ParsedRule $rule, string $inputType): null|array
    {
        return match ($rule->rule()) {
            'alnum' => ['pattern', '[a-zA-Z0-9]+'],
            'alpha' => ['pattern', '[a-zA-Z]+'],
            'alphabetic' => ['pattern', '[\pL\pM]+'],
            'alphabeticNum' => ['pattern', '[\pL\pM\pN]+'],
            'maxLen' => ['maxlength', $rule->parameters()['rule_parameters'][0] ?? '0'],
            'maxNum' => ['max', $rule->parameters()['rule_parameters'][0] ?? '0'],
            'minLen' => ['minlength', $rule->parameters()['rule_parameters'][0] ?? '0'],
            'minNum' => ['min', $rule->parameters()['rule_parameters'][0] ?? '0'],
            'notEmpty' => ['required', null],
            'notNull' => ['required', null],
            'required' => ['required', null],
            default => null,
        };
    }
    
    /**
     * Returns the rule message.
     * 
     * @param ParsedRule $rule
     * @return string
     */
    protected function getRuleMessage(ParsedRule $rule): string
    {
        return match ($rule->rule()) {
            'alnum' => Rule\Strings::MESSAGES['alnum'] ?? '',
            'alpha' => Rule\Strings::MESSAGES['alpha'] ?? '',
            'alphabetic' => Rule\Strings::MESSAGES['alphabetic'] ?? '',
            'alphabeticNum' => Rule\Strings::MESSAGES['alphabeticNum'] ?? '',
            'maxLen' => Rule\Length::MESSAGES['max'] ?? '',
            'maxNum' => Rule\Number::MESSAGES['max'] ?? '',
            'minLen' => Rule\Length::MESSAGES['min'] ?? '',
            'minNum' => Rule\Number::MESSAGES['min'] ?? '',
            default => '',
        };
    }

    /**
     * Returns the message for the given rule.
     * 
     * @param ParsedRule $rule
     * @param string $inputName
     * @return string
     */
    protected function message(ParsedRule $rule, string $inputName): string
    {
        if (
            isset($rule->parameters()['error'])
            && is_string($rule->parameters()['error'])
        ) {
            $message = $rule->parameters()['error'];
        } else {
            $message = $this->getRuleMessage($rule);
        }
        
        if ($message === '') {
            return $message;
        }

        return $this->modifiers->modify(new Message(
            level: 'error',
            message: $message,
            context: $rule->parameters(),
            key: $inputName,
        ))->message();
    }
    
    /**
     * Returns true if the given attribute is allowed for the input type, otherwise false.
     * 
     * @param array $attribute
     * @param string $inputType
     * @return bool
     */
    protected function isAllowedForInputType(array $attribute, string $inputType): bool
    {
        [$name] = $attribute;
        
        return match ($name) {
            'pattern' => in_array($inputType, [
                'text', 'search', 'url', 'tel', 'email', 'password',
            ]),
            'required' => in_array($inputType, [
                'text', 'search', 'url', 'tel', 'email', 'password', 'date', 'month',
                'week', 'time', 'datetime-local', 'number', 'checkbox', 'radio',
                'select', 'textarea',
            ]),
            'minlength', 'maxlength' => !in_array($inputType, ['select']),
            'min', 'max', 'step' => in_array($inputType, [
                'date', 'month', 'week', 'time', 'datetime-local', 'number', 'range',
            ]),
            default => false,
        };
    }
}