# Validation Service

The Validation Service provides an easy way to validate data.

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
- [Documentation](#documentation)
    - [Validating](#validating)
        - [Single value](#single-value)
        - [Multiple values](#multiple-values)
        - [Nested values](#nested-values)
        - [Rules Definition](#rules-definition)
    - [Validator](#validator)
        - [Create Validator](#create-validator)
        - [Validator Interface](#validator-interface)
        - [Rules Aware](#rules-aware)
    - [Validation](#validation)
        - [Validation Interface](#validation-interface)
        - [Error Messages](#error-messages)
        - [Validated Data](#validated-data)
    - [Rules](#rules)
        - [Rules Interface](#rules-interface)
        - [Default Rules](#default-rules)
            - [Available Rules](#available-rules)
            - [Adding Rules](#adding-rules)
        - [Custom Rules](#custom-rules)
    - [Rule](#rule)
        - [Rule Interface](#rule-interface)
        - [Passes Rule](#passes-rule)
        - [Custom Rule](#custom-rule)
    - [Rules Parser](#rules-parser)
        - [Default Rules Parser](#default-rules-parser)
        - [Custom Rules Parser](#custom-rules-parser)
    - [Messages](#messages)
        - [Messages Factory](#messages-factory)
        - [Message Translation](#message-translation)
- [Credits](#credits)
___

# Getting started

Add the latest version of the validation service running this command.

```
composer require tobento/service-validation
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design

# Documentation

## Validating

### Single value

Easily validate a single value.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;

$validator = new Validator();

var_dump($validator instanceof ValidatorInterface);
// bool(true)

$validation = $validator->validating(
    value: 'foo',
    rules: 'alphaStrict|minLen:2',
    data: [],
    key: null
);

var_dump($validation instanceof ValidationInterface);
// bool(true)
```

Check out [Validator](#validator) to learn more about the Validator.\
Check out [Validation](#validation) to learn more about the ValidationInterface.

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **value** | The value to validate. |
| **rules** | The rules definition. See [Rules Definition](#rules-definition) for more detail. |
| **data** | Any data used by certain rules for validation. See [Rules](#rules) for more detail. |
| **key** | Used for error messages. See [Error Messages](#error-messages) for more detail. |

### Multiple values

Validate multiple values.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;

$validator = new Validator();

var_dump($validator instanceof ValidatorInterface);
// bool(true)

$validation = $validator->validate(
    data: [
        'title' => 'Product',
        'color' => 'blue',
    ],
    rules: [
        'title' => 'alpha',
        'color' => 'in:blue:red:green',        
    ]
);

var_dump($validation instanceof ValidationInterface);
// bool(true)
```

Check out [Validator](#validator) to learn more about the Validator.\
Check out [Validation](#validation) to learn more about the ValidationInterface.

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **data** | The data to validate. |
| **rules** | The rules definitions. See [Rules Definition](#rules-definition) for more detail. |

### Nested values

If the incoming values contains "nested" data, you may specify these attributes in your rules using "dot" syntax:

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;

$validator = new Validator();

var_dump($validator instanceof ValidatorInterface);
// bool(true)

$validation = $validator->validate(
    data: [
        'title' => 'Product',
        'meta' => [
            'color' => 'blue',
        ],
    ],
    rules: [
        'title' => 'alpha',
        'meta.color' => 'required|in:blue:red:green',        
    ]
);

var_dump($validation instanceof ValidationInterface);
// bool(true)
```

Check out [Validator](#validator) to learn more about the Validator.\
Check out [Validation](#validation) to learn more about the ValidationInterface.

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **data** | The data to validate. |
| **rules** | The rules definitions. See [Rules Definition](#rules-definition) for more detail. |

### Rules Definition

The [Default Rules Parser](#default-rules-parser) supports the following rules definition.\
You may also check out the [Default Rules](#default-rules) to learn more about the rules it provides.\
If you add rules "lazy" with dependencies you will need to use the AutowiringRuleFactory for resolving see [Default Rules](#default-rules).

**string definition**

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => 'minLen:2|alpha',
    ]
);
```

**array definition with string rules**

If you need to define additional rule parameters or custom error messages, wrap the rule into an array:

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => [
            // single or multiple rules
            'required|alpha',
            
            // using array with string rule and custom parameters.
            ['minLen:3', 'error' => 'Custom error message'],
            
            // using array with string rule but seperate rule parameters and custom parameters.
            ['minLen', [3], 'error' => 'Custom error message'],
        ],
    ]
);
```

**object rules**

You may define object rules implementing the [Rule Interface](#rule-interface):

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\Rule\Same;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => [
            // single or multiple rules
            'required|minLen:2',
            
            new Same(),
            
            // using array for custom parameters:
            [new Same(), 'error' => 'Custom error message'],
            
            // using array for lazy rule:
            [[Rule::class], [3], 'error' => 'Custom error message'],
            
            // lazy rule with unresolvable class params:
            // [[Rule::class, ['name' => 'value']], [3], 'error' => 'Custom error message'],
        ],
    ]
);
```

**object rules with different validation method**

You may define object rules with different validation methods:

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\Rule\Length;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => [
            // single or multiple rules
            'required|alpha',
            
            // calls the min method for validation:
            [[new Length(), 'min'], [3], 'error' => 'Custom error message'],
            
            // lazy rule
            [[Length::class, 'min'], [3], 'error' => 'Custom error message'],
            
            // lazy rule with unresolvable class params:
            [[Length::class, 'min', ['name' => 'value']], [3], 'error' => 'Custom error message'],
            
            // lazy rule with unresolvable class params without method to call:
            // [[Rule::class, ['name' => 'value']], [3], 'error' => 'Custom error message'],
        ],
    ]
);
```

**Parameters**

For each rule you can define custom parameters.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\Rule\Length;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => [
            [
                'minLen:3',
                'error' => ':attribute must at least contain :parameters[0] chars',
                
                // you might want a custom value for the attribute:
                ':attribute' => 'The TITLE',
                
                // you might need a custom value:
                ':parameters[0]' => 3,
                
                // global modifier parameters:
                'limit_length' => 100,
            ],
        ],
    ]
);
```

**Global parameters**

Sometimes you may need custom parameters for all rules.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\Rule\Length;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Product',
    ],
    rules: [
        'title' => [
            // single or multiple rules
            'required|alpha',
            
            // calls the min method for validation:
            [[new Length(), 'min'], [3]],
            
            // global error message:
            'error' => 'Error message',
            
            // global replacement parameters for messages:
            ':attribute' => 'The TITLE',
            
            // global modifier parameters:
            'limit_length' => 100,
        ],
    ]
);
```

## Validator

### Create Validator

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\RulesInterface;
use Tobento\Service\Validation\RulesParserInterface;
use Tobento\Service\Validation\RulesAware;
use Tobento\Service\Message\MessagesFactoryInterface;

$validator = new Validator(
    rules: null, // null|RulesInterface
    rulesParser: null, // null|RulesParserInterface
    messagesFactory: null // null|MessagesFactoryInterface
);

var_dump($validator instanceof ValidatorInterface);
// bool(true)

var_dump($validator instanceof RulesAware);
// bool(true)
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **rules** | Provides the rules for validation. See [Rules](#rules) for more detail. |
| **rulesParser** | Parses the rules. See [Rules Parser](#rules-parser) for more detail. |
| **messagesFactory** | Creates the error messages. See [Messages Factory](#messages-factory) for more detail. |

### Validator Interface

```php
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Collection\Collection;

interface ValidatorInterface
{
    public function validating(
        mixed $value,
        string|array $rules,
        array|Collection $data = [],
        null|string $key = null
    ): ValidationInterface;
    
    public function validate(mixed $data, array $rules): ValidationInterface;
}
```

Check out [Validating](#validating) to learn more about the methods.\
Check out [Collection Service](https://github.com/tobento-ch/service-collection) to learn more it.

### Rules Aware

```php
use Tobento\Service\Validation\RulesInterface;

interface RulesAware
{
    public function rules(): RulesInterface;
}
```

Check out [Rules Interface](#rules-interface) to learn more about the RulesInterface.


## Validation

### Validation Interface

```php
use Tobento\Service\Validation\ValidationInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Message\MessagesInterface;
use Tobento\Service\Collection\Collection;

interface ValidationInterface
{
    public function isValid(): bool;
    
    public function errors(): MessagesInterface;  
    
    public function data(): Collection;
    
    public function valid(): Collection;
    
    public function invalid(): Collection;
    
    public function rule(): null|RuleInterface;
}
```

**Methods explanation**

| Parameter | Description |
| --- | --- |
| **isValid** | Returns true if the validation is valid, otherwise false. |
| **errors** | Returns the error messages. See [Message Service](https://github.com/tobento-ch/service-message) for more detail. |
| **data** | Returns the data to validate. See [Collection Service](https://github.com/tobento-ch/service-collection) for more detail. |
| **valid** | Returns the valid data. See [Collection Service](https://github.com/tobento-ch/service-collection) for more detail. |
| **invalid** | Returns the invalid data. See [Collection Service](https://github.com/tobento-ch/service-collection) for more detail. |
| **rule** | Returns the rule or null. |

### Error Messages

By default, the rules keys are used as the :attribute parameter when defined in the error messages.\
For translation reason, it is not recommended to write messages like "The :attribute must ...", it is better to add "The title" in the :attribute parameter because "The" might be different for an attribute name depending on the language.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Message\MessagesInterface;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Pr',
        'color' => 'green',
    ],
    rules: [
        'title' => 'minLen:3|alpha',
        'color' => 'in:blue:red',        
    ]
);

$errors = $validation->errors();

var_dump($errors instanceof MessagesInterface);
// bool(true)
```

**Retrieving the first error message for a data key**

```php
use Tobento\Service\Message\MessageInterface;

$errors = $validation->errors();

$error = $errors->key('title')->first();

var_dump($error instanceof MessageInterface);
// bool(true)
    
echo $error;
// The title must at least contain 3 chars.
```

Check out the [Message Service](https://github.com/tobento-ch/service-message) to learn more about messages in general.

**Retrieving all error messages for all data keys**

```php
use Tobento\Service\Message\MessageInterface;

$errors = $validation->errors();

foreach($errors->key('title')->all() as $error) {
    var_dump($error instanceof MessageInterface);
    // bool(true)
}
```

Check out the [Message Service](https://github.com/tobento-ch/service-message) to learn more about messages in general.

**Determine if messages exist for a data key**

```php
$errors = $validation->errors();

var_dump($errors->key('title')->has());
// bool(true)
```

Check out the [Message Service](https://github.com/tobento-ch/service-message) to learn more about messages in general.

**Custom error message**

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Pr',
        'color' => 'green',
    ],
    rules: [
        'title' => [
            'alpha',
            ['minLen', [3], 'error' => ':attribute must contain at least :parameters[0] chars!']
        ],
        'color' => 'in:blue:red',        
    ]
);

$errors = $validation->errors();

echo $errors->key('title')->first();
// The title must contain at least 3 chars!
```

**Custom error message parameters**

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Pr',
        'color' => 'green',
    ],
    rules: [
        'title' => [
            'alpha',
            [
                'minLen',
                [3],
                ':attribute' => 'The TITLE',
                ':parameters[0]' => 3,
                'error' => ':attribute must contain at least :parameters[0] chars!'
            ]
        ],
        'color' => 'in:blue:red',        
    ]
);

$errors = $validation->errors();

echo $errors->key('title')->first();
// The TITLE must contain at least 3 chars!
```

**Global custom error message parameters**

You might want to define global message parameters for all rules defined:

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Pr',
        'color' => 'green',
    ],
    rules: [
        'title' => [
            'minLen:3|alpha',
            ':attribute' => 'The TITLE',
            // you might even set a global message for all rules.
            'error' => ':attribute is invalid',
        ],
        'color' => 'in:blue:red',
    ]
);

$errors = $validation->errors();

echo $errors->key('title')->first();
// The TITLE is invalid.
```

**Skipping first parameter**

You might need to skip the first value of the parameters by declaring it as **:parameters[-1]**.

```php
use Tobento\Service\Validation\Validator;

$validation = (new Validator())->validate(
    data: [
        'title' => '',
        'color' => 'green',
    ],
    rules: [
        'title' => [
            'required_ifIn:color:green:red',
            'error' => ':attribute is required as :parameters[0] is in list :parameters[-1].',
        ],
        'color' => 'in:blue:red',
    ]
);

$errors = $validation->errors();

echo $errors->key('title')->first();
// The title is required as color is in list green, red.
```

### Validated Data

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Collection\Collection;

$validation = (new Validator())->validate(
    data: [
        'title' => 'Pr',
        'color' => 'green',
    ],
    rules: [
        'title' => 'minLen:3|alpha',
        'color' => 'in:blue:red',        
    ]
);

// all validated data:
var_dump($validation->data() instanceof Collection);
// bool(true)

// all valid data:
var_dump($validation->valid() instanceof Collection);
// bool(true)

// all invalid data:
var_dump($validation->invalid() instanceof Collection);
// bool(true)
```

Check out the [Collection Service](https://github.com/tobento-ch/service-collection#collection) to learn more about Collection in general.

## Rules

## Rules Interface

```php
use Tobento\Service\Validation\RulesInterface;
use Tobento\Service\Validation\RuleInterface;
use Tobento\Service\Validation\RuleNotFoundException;
use Tobento\Service\Validation\InvalidRuleException;

/**
 * RulesInterface
 */
interface RulesInterface
{
    /**
     * Add a rule.
     *
     * @param string $name
     * @param mixed $rule
     * @return static $this
     */
    public function add(string $name, mixed $rule): static;

    /**
     * Returns the rule based on the specified rule.
     *
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws RuleNotFoundException
     * @throws InvalidRuleException
     */
    public function get(mixed $rule): RuleInterface;
}
```

### Default Rules

```php
use Tobento\Service\Validation\DefaultRules;
use Tobento\Service\Validation\RulesInterface;
use Tobento\Service\Validation\RuleFactoryInterface;

$rules = new DefaultRules(
    ruleFactory: null, //null|RuleFactoryInterface
);

var_dump($rules instanceof RulesInterface);
// bool(true)
```

**With autowiring rule factory**

The autowiring rule factory is needed if you define or add rules "lazy" with dependencies.

```php
use Tobento\Service\Validation\DefaultRules;
use Tobento\Service\Validation\AutowiringRuleFactory;

// Any PSR-11 container
$container = new \Tobento\Service\Container\Container();

$rules = new DefaultRules(
    ruleFactory: new AutowiringRuleFactory($container);
);
```

#### Available Rules

The following rules are available out of the box:

| Rule | Parameters Example | Allows Empty | Description |
| --- | --- | --- | --- |
| **alnum** | | true | The value must be entirely alpha [a-zA-Z] characters and/or numbers. |
| **alpha** | | true | The value must be entirely alpha [a-zA-Z] characters. |
| **alphabetic** | | true | The value must be entirely alphabetic characters. |
| **alphabeticNum** | | true | The value must be entirely alphabetic characters and/or numbers. |
| **array** | | true | The value must be an array. |
| **bool** | | true | The value must be a bool.<br>valid: true, false, 1, 0, "1", "0" |
| **date** | | true | The value must be a valid date. |
| **dateAfter:date** | dateAfter:2021-12-24<br>dateAfter:2021-12-24:true (same time is past) | true | The value must be a date after the date set. |
| **dateBefore:date** | dateBefore:2021-12-24<br>dateBefore:2021-12-24:true (same time is past) | true | The value must be a date before the date set. |
| **dateFormat:format** | dateFormat:Y-m-d:Y.m.d | true | The value must match any of the date formats. |
| **decimal** | | true | The value must be a decimal.<br>**valid:** 23.00, '22.50', 0, -0.00000, '-0.1', 50, '55' |
| **digit** | | true | The value must only contain digits [0-9]. |
| **each:list** | each:blue:red (starting for 0)<br> each:5=>blue:8=>red | true | Each value in the array must be within the list of values provided with the same keys. |
| **eachIn:list** | eachIn:blue:red | true | Each value in the array must be within the list of values provided. |
| **eachWith:key_rules:value_rules** | eachWith:int/minNum;1:alpha/maxLen;3<br>['key' => 'int', 'value' => 'required\|alpha'] | true | The value must be an with the rules passing. |
| **email** | | true | The value must be a valid email address. |
| **float** | | true | The value must be a float. |
| **in:list** | in:blue:red | true | The value must be in the list provided. |
| **int** | | true | The value must be a int. |
| **json** | | true | The value must be a valid JSON string. |
| **maxItems:number** | maxItems:5 | true | The array must be at most the number of items. |
| **maxLen:length** | maxLen:5 | true | The value must at most have the maximun length set. |
| **maxNum:number** | maxNum:5 | true | The value must be at most the number set. |
| **minItems:number** | minItems:5 | true | The array must have at least the number of items. |
| **minLen:length** | minLen:5 | true | The value must at least have the minimum length set. |
| **minNum:number** | minNum:5 | true | The value must be at least the number set. |
| **notEmpty** | | true | The value must be not be empty. |
| **notNull** | | true | The value must be not be null. |
| **numeric** | | true | The value must be numeric. |
| **regex:pattern** | regex:#^[a-z0-9]+$# | true | The value must match the pattern. |
| **required** | | false | The value must not be empty. |
| **required_ifEqual:field:value** | required_ifEqual:role:admin | false | Required when field is equal to value. |
| **required_ifIn:field:value:value1** | required_ifIn:role:admin:editor | false | Required when field is one of the values. |
| **required_with:field:field1** | required_with:firstname:lastname | false | Required when one of the fields is present and not empty. |
| **required_without:field:field1** | required_without:firstname:lastname | false | Required when one of the fields is not present and not empty. |
| **same:field** | same:user.password | true | The value must be the same as the field. |
| **scalar** | | true | The value must be scalar. |
| **string** | | true | The value must be a string. |
| **url** | | true | The value must be a valid URL. |

#### Adding Rules

You may add additional rules by the following way. If you add rules "lazy" with dependencies you will need to use the AutowiringRuleFactory for resolving.

```php
use Tobento\Service\Validation\DefaultRules;
use Tobento\Service\Validation\RulesInterface;
use Tobento\Service\Validation\AutowiringRuleFactory;
use Tobento\Service\Validation\Rule\Same;
use Tobento\Service\Validation\Rule\Type;

// Any PSR-11 container
$container = new \Tobento\Service\Container\Container();

$rules = new DefaultRules(
    ruleFactory: new AutowiringRuleFactory($container);
);

$rules = new DefaultRules();

$rules->add('same', new Same());

// Lazy:
$rules->add('same', Same::class);

// Custom method:
$rules->add('bool', [new Type(), 'bool']);

// Lazy custom method:
$rules->add('bool', [Type::class, 'bool']);

// Lazy custom method with unresolvable parameters:
// $rules->add('rule', [Rule::class, 'bool', ['name' => 'value']]);

// Lazy with unresolvable parameters:
// $rules->add('rule', [Rule::class, ['name' => 'value']]);
```

### Custom Rules

You may write your own rules class or adjusting the default rules for your needs.

```php
use Tobento\Service\Validation\DefaultRules;

class CustomDefaultRules extends DefaultRules
{
    protected function getDefaultRules(): array
    {
        $rules = parent::getDefaultRules();
        
        // adding or overwriting rules.
        $rules['bool'] = [\Tobento\Service\Validation\Rule\Type::class, 'bool'];
        
        return $rules;
    }
}

$rules = new CustomDefaultRules();
```

## Rule

### Rule Interface

```php
use Tobento\Service\Validation\RuleInterface;

/**
 * RuleInterface
 */
interface RuleInterface
{
    /**
     * Skips validation depending on value and rule method.
     * 
     * @param mixed $value The value to validate.
     * @param string $method
     * @return bool Returns true if skip validation, otherwise false.
     */
    public function skipValidation(mixed $value, string $method = 'passes'): bool;
    
    /**
     * Determine if the validation rule passes.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool;
    
    /**
     * Returns the validation error messages.
     * 
     * @return array
     */
    public function messages(): array;    
}
```

### Passes Rule

With the Passes rule you can create any custom rule.

```php
use Tobento\Service\Validation\Rule\Passes;

$validation = $validator->validating(
    value: 'foo',
    rules: [
        // rule does pass:
        new Passes(passes: true),
        
        // rule does not pass:
        new Passes(passes: false),
        
        // rule does pass:
        new Passes(passes: fn (mixed $value): bool => $value === 'foo'),
        
        // using static new method:
        Passes::new(passes: true),
    ],
);
```

**Passes parameters**

The following parameters are available:

```php
use Tobento\Service\Validation\Rule\Passes;
use Tobento\Service\Validation\ValidatorInterface;
use Tobento\Service\Validation\ValidationInterface;

$rule = new Passes(passes: function(
    mixed $value,
    array $parameters,
    ValidatorInterface $validator,
    ValidationInterface $validation): bool
{
    return true;
});
```

If you have set up the validator with the [autowiring rule factory](#default-rules), the ```passes``` and ```skipValidation``` callable are autowired:

```php
use Tobento\Service\Validation\Rule\Passes;

$rule = new Passes(passes: function(mixed $value, SomeService $service): bool {
    return true;
});
```

**Ensure Declared Closure Type**

By default, the declared type of a closure ```$value``` parameter will be automatically verified. If it does not match the input value type, the rule does not pass and the closure will never be executed.

```php
use Tobento\Service\Validation\Rule\Passes;

$rule = new Passes(passes: fn (string|int $value): bool {
    return true;
});

// you may deactivate it, but then you will need to declare the value type as mixed:
$rule = new Passes(
    passes: fn (mixed $value): bool {
        return true;
    },
    verifyDeclaredType: false,
);
```

**Custom error message**

You may specify a custom error message:

```php
use Tobento\Service\Validation\Rule\Passes;

$rule = new Passes(
    passes: true,
    errorMessage: 'Custom error message',
);
```

**Skip validation**

You may use the skipValidation parameter in order to skip validation under certain conditions:

```php
$validation = $validator->validating(
    value: 'foo',
    rules: [
        // skips validation:
        new Passes(passes: true, skipValidation: true),
        
        // does not skip validation:
        new Passes(passes: true, skipValidation: false),
        
        // skips validation:
        new Passes(passes: true, skipValidation: fn (mixed $value): bool => $value === 'foo'),
    ],
);
```

### Custom Rule

```php
use Tobento\Service\Validation\Rule;

class ListRule extends Rule
{
    /**
     * The error messages.
     */
    public const MESSAGES = [
        'passes' => ':attribute must be in list :parameters',
    ];
    
    /**
     * Determine if the validation rule passes.
     * 
     * @param mixed $value The value to validate.
     * @param array $parameters Any parameters used for the validation.
     * @return bool
     */
    public function passes(mixed $value, array $parameters = []): bool
    {
        return in_array($value, $parameters);
    }
}
```

**With multiple validation methods**

See Rule\Strings for demo.

**Needs Validation for validation**

See Rule\Arr for demo.

**Needs Validator for validation**

See Rule\Arr for demo.

**Skip validation when passes**

See rules for demo.


## Rules Parser

The role of the rules parser is to parse the [Rules Definition](#rules-definition).

### Default Rules Parser

See the [Rules Definition](#rules-definition) for more detail.

### Custom Rules Parser

You may write your own parser for your needs implementing the following interface.

```php
use Tobento\Service\Validation\RulesParserInterface;
use Tobento\Service\Validation\ParsedRule;
use Tobento\Service\Validation\RulesParserException;

interface RulesParserInterface
{
    /**
     * Parses the rules.
     * 
     * @param string|array $rules
     * @return array<int, ParsedRule>
     *
     * @throws RulesParserException
     */
    public function parse(string|array $rules): array;
}
```

## Messages

Messages are used for the validation [Error Messages](#error-messages).\
Check out the [Message Service](https://github.com/tobento-ch/service-message) to learn more about messages in general.

### Messages Factory

With the message factory you can fully control how messages are created and modified.

```php
use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Message\MessagesFactoryInterface;
use Tobento\Service\Message\MessageFactoryInterface;
use Tobento\Service\Message\ModifiersInterface;
use Psr\Log\LoggerInterface;

$messagesFactory = new MessagesFactory(
    messageFactory: null, // null|MessageFactoryInterface
    modifiers: null, // null|ModifiersInterface
    logger: null, // null|LoggerInterface
);

var_dump($messagesFactory instanceof MessagesFactoryInterface);
// bool(true)

$modifiers = $messagesFactory->modifiers();

var_dump($modifiers instanceof ModifiersInterface);
// bool(true)
```

**Parameters explanation**

| Parameter | Description |
| --- | --- |
| **messageFactory** | Creating the messages. See [Message Service - Message Factory](https://github.com/tobento-ch/service-message#message-factory) for more detail. |
| **modifiers** | With modifiers you can modify messages such as translating for instance. See [Message Service - Modifiers](https://github.com/tobento-ch/service-message#modifiers) for more detail. |
| **logger** | You might set a looger to log messages. |

**Default modifiers**

If you do not set modifiers on the factory, the following modifiers are added:

```php
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Validation\Message\RuleParametersModifier;
use Tobento\Service\Message\Modifier\ParameterReplacer;

$modifiers = new Modifiers(
    // maps :attribute, :value, :parameters
    // based on the rule parameters
    new RuleParametersModifier(),
    
    // Default parameter replacer
    new ParameterReplacer(),
);
```

### Message Translation

If you want to translate messages you may use the translator modifiers:\
First you will need to install [Translation Service](https://github.com/tobento-ch/service-translation) though.

```php
use Tobento\Service\Validation\Validator;
use Tobento\Service\Validation\Message\MessagesFactory;
use Tobento\Service\Validation\Message\RuleParametersModifier;
use Tobento\Service\Translation;
use Tobento\Service\Message\Modifier\Translator;
use Tobento\Service\Message\Modifier\ParameterTranslator;
use Tobento\Service\Message\Modifiers;
use Tobento\Service\Message\Modifier\ParameterReplacer;

$translator = new Translation\Translator(
    new Translation\Resources(
        new Translation\Resource('*', 'de', [
            'The :attribute must only contain letters [a-zA-Z]' => ':attribute muss aus Buchstaben [a-zA-Z] bestehen.',
            'title' => 'Titel',
        ]),       
    ),
    new Translation\Modifiers(),
    new Translation\MissingTranslationHandler(),
    'de',
);

$messagesFactory = new MessagesFactory(
    modifiers: new Modifiers(
        new Translator(translator: $translator, src: '*'),
        new RuleParametersModifier(),
        new ParameterTranslator(
            parameters: [':attribute'],
            translator: $translator,
            src: '*'
        ),
        new ParameterReplacer(),
    )
);

$validator = new Validator(messagesFactory: $messagesFactory);

$validation = $validator->validate(
    data: [
        'title' => 'P3',
    ],
    rules: [
        'title' => 'alpha',      
    ]
);

$errors = $validation->errors();

var_dump($errors->key('title')->first()->message());
// string(44) "Titel muss aus Buchstaben [a-zA-Z] bestehen."
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)