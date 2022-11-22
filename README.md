# Ensure - A PHP validation library
--------------------------------------------

- [Installation](#installation)
- [Basic example](#basic-example)
- [Managing rules](#managing-rules)
- [Managing values](#managing-values)
- [Getting the result \& errors](#getting-the-result--errors)
- [Managing error templates](#managing-error-templates)
    - [Error templates explained](#error-templates-explained)
    - [Set fancy field names](#set-fancy-field-names)
- [Managing validators](#managing-validators)
- [Custom validators](#custom-validators)
    - [Closure](#closure)
    - [Fully qualified class name](#fully-qualified-class-name)
    - [Array with fully qualified class name \& method](#array-with-fully-qualified-class-name--method)
    - [Named function](#named-function)
    - [Using other field values in your validator](#using-other-field-values-in-your-validator)
- [Class resolver](#class-resolver)
- [The factory](#the-factory)
- [Existing rules](#existing-rules)
- [Rulesets](#rulesets)

## Installation

Using Composer:

```cli
composer require jsl/ensure
```


## Basic example

```php
use Jsl\Ensure\Ensure;

$values = [
    'name' => 'Mr Magoo',
    'age'  => 77,
];

$rules = [
    'name' => ['required', 'type' => 'string', 'minSize' => 20],
    'age'  => ['required', 'type' => 'integer', 'minSize' => 80],
];

// Create a new validation instance
$ensure = new Ensure($rules, $values);

// Validate the values against the rules
$result = $ensure->validate();

// Check if the validation was successful
$result->isValid(); // Returns true or false

// If validation failed, get array of validation errors
print_r($result->getErrors());

// Returns:
// 
// Array
// (
//     [name] => Array
//         (
//             [0] => Size of name must be at least 20
//         )
//     [age] => Array
//         (
//             [0] => Size of age must be at least 80
//         )
// )
```


## Managing rules

```php
$rules = [
    'name' => ['required', 'type' => 'string', 'minSize' => 20],
    'age'  => ['required', 'type' => 'integer', 'minSize' => 80],
];

// Pass them through the constructor
$ensure = new Ensure($rules, $values);

// Add/replace a rule on an existing instance
// setFieldRule(string $field, string $rule, ...$args)
$ensure->setFieldRule('name', 'minSize', 10);

// Add/replace multiple rules on an existing instance
// setFieldRules(string $field, array $rules)
$ensure->setFieldRules('age', ['type' => 'string', 'minSize' => 70]);

// Remove a field rule
// removeRule(string $field, string $rule)
$ensure->removeFieldRule('age', 'type');
```

## Managing values

```php
$values = [
    'name' => 'Mr Magoo',
    'age'  => 77,
];

// Pass them through the constructor
// You can pass $_POST or $_GET directly as well
$ensure = new Ensure($rules, $values);

// Add/replace a value on an existing instance
// seFieldValue(string $field, mixed $value)
$ensure->setFieldValue('name', 'Mrs Magoo');

// Replace all values
// replaceValues(array $values)
$ensure->replaceValues([
    'name' => 'Mrs Magoo', 
    'age' => 70
]);
```

## Getting the result & errors

```php
// Validate the values against the rules and get the result
$result = $ensure->validate();

// Check if the validation was successful
$result->isValid(); // Returns true or false

// Getting potential errors (returns all failed rules for the fields)
// This will return an array [fieldname => [error1, error2, ...]]
$errors = $result->getErrors();

// To only get the first failed rule, pass "true" to the method
// This will return an array [fieldname => error1, fieldname2 => error1, ...]
$errors = $result->getErrors(true);
```


### Managing error templates
Since you might not like the default error templates/messages the validators return, there are several ways you can customize them

```php
// Set the default error template for a specific rule
// This will replace the error template for this rule for all fields
$ensure->setRuleTemplate('rule', '{field} has failed {a:0}');

// Set the default error template for a specific rule for a specific field
// This will only replace the error template for this rule for the specific field
$ensure->setFieldRuleTemplate('field', 'rule', '{field} has failed {a:0}');

// Set a single error template for a specific field, regardless of which rule failed
// Note: This will only replace rule templates, not field rule templates set with setFieldRuleTemplate()
$ensure->setFieldTemplate('field', 'rule', '{field} has failed {a:0}');

// To remove a previously added error template, pass in null as the message
// This works on all the above methods
$ensure->setRuleTemplate('rule', null);
```

**Note:** These methods are also available on the `Result` instance, so you don't need to define them on the Ensure instance before the validation.


#### Error templates explained

Error templates is the error message returned if a rule/field fails. They can contain placeholders that will be resolved when the errors are rendered


```php
$template = '{field} must be {a:0}';

// {field} will be replaced with the field name
// {a:0} will be replaced with the value from the first argument
// {a:1} will be replaced with the value from the second argument
// ...you can have as many `{a:x}` as there are arguments for the rule
```

**Note:** Only the `{field}` placeholder will be replaced for templates added using `setFieldTemplate()` since that is a generic message for that field and not for a specific rule.


#### Set fancy field names
When showing errors to users (specifically on a web page), you don't want to use the input names. To solve this, you can set "fancy" field names which will be used for the `{field}` when rendering the error templates

```php
// Set multiple fancy names in one go
$ensure->setFancyNames([
    'name' => 'The name',
    'age' => 'The age',
]);
```
Another way to set the fancy names is to use the rule `as`.

```php
// Either through the rules array
$rules = [
    'name' => [
        'required',
        'as' => 'A fancy field name', 
    ],
];

// Or adding it as any other rule
$ensure->setFieldRule('fieldname', 'as', 'Fancy field name');
```

## Managing validators

The validators are the heart and soul in a validation library. Without them, the rest would be pretty pointless.

If you're missing/need some validator that isn't included in the default set, you can easily add your own. You can add as many as you want

### Custom validators

To add validators, you can use

    // addValidator(string $name, mixed $callback, ?string $template = null)
    $ensure->addValidator($name, $callback, $optionalTemplate);

* `$name` will be the rule name for the validator.  
* `$callback` is the validator. It can be one of many types of callbacks. See below for more info.  
* `$template` is the error template to return if the validator fails. It's optional, but might be nice to have

> Your validator should return `true` on pass and `false` on fail.

You can add as many custom validators as you want, and you can create them in many different ways:

A validator can be a callable, fully qualified class name, class instance, array with class name/instance and method.

No matter which type of callback you use, you can pass the error template as the third parameter when adding the validator. I however omit them here for readability.



#### Closure
The easiest way to add a simple validator

```php
$ensure->addValidator('between', function ($value, $min, $max): bool {
    return $value >= $min && $value <= $max;
});
```

#### Fully qualified class name
This requires the class to use the magic method `__invoke()`

```php
class BetweenValidator
{
    public function __invoke($value, $min, $max): bool
    {
        return $value >= $min && $value <= $max;;
    }
}

$ensure->addValidator('between', BetweenValidator::class);

// You can also pass it as an instance

$ensure->addValidator('between', new BetweenValidator);
```



#### Array with fully qualified class name & method
```php
class MyValidators
{
    public function myBetweenMethod($value, $min, $max): bool
    {
        return $value >= $min && $value <= $max;
    }
}

$ensure->addValidator('between', [MyValidators::class, 'myBetweenMethod']);

// You can also use an instance

$ensure->addValidator('between', [new MyValidators, 'myBetweenMethod']);
```

#### Named function
```php
function myBetweenFunc($value, $min, $max): bool
{
    return $value >= $min && $value <= $max;
}

$ensure->addValidator('between', 'myBetweenFunc');
```

#### Using other field values in your validator

If you want to access other field values in your validator, the options are a bit more limited. Create a class that implements the `Jsl\Ensure\Contracts\ValidatorInterface`

The easiest way is to extend `Jsl\Ensure\Abstracts\Validator`, which implements that interface with all necessary methods

Imagine the rule:
```php
$rule = [
    'theValueField' => [
        'sameAsField' => ['anotherValueField']
    ],
];
```
The implementation could look like this:

```php
class AreFieldsSameValidator extends \Jsl\Ensure\Abstracts\Validator
{
    /**
     * Check if two values are the same
     *
     * @param mixed $value  The value
     * @param string $field The name of the other field
     *
     * @return bool
     */
    public function __invoke($value, $field): bool
    {
        return $value === $this->getValue($field);
    }


    /**
     * For classes implementing the ValidatorInstance,
     * we should add the error template like this
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '{field} must be the same as {a:0}';
    }
}

$ensure->addValidator('sameAsField', AreFieldsSameValidator::class);
```

### Class resolver

As default, Ensure will create an instance of any fully qualified class name in the easiest possible way:

```php
return new $className;
```

This works perfectly well, unless you want to use some IoC/Dependency container to instantiate your validator classes and inject some dependencies.

Luckily, you can add your own class resolver in form of a closure.

It could look something like this:

```php
$ensure->setClassResolver(function (string $className) use ($yourContainer) {
    return $yourContainer->has($className)
        ? $yourContainer->get($className)
        : new $className;
});
```

Now all validator classes will be resolved using that closure (including the default validators)

## The factory

So far, we've only been talking about how to add error templates and validators to a specific Ensure instance.

By using the factory, you can do all those things on a higher level, making sure all new instances inherits those settings:

```php
use Jsl\Ensure\EnsureFactory;

$factory = new EnsureFactory;

$factory->setFieldTemplate(...)
    ->setFieldRuleTemplate(...)
    ->setRuleTemplate(...)
    ->setClassResolver(...)
    ->addValidator(...);

// To get an Ensure instance that inherits those settings
$ensure = $factory->create($rules, $data);
```

## Existing rules
These are the default rules.  
If you want to replace the implementation of any of them, just add a validator with the same name.

> If a rule only have one argument, you can pass it directly:  
> `'rule' => 'arg'`  
> If a rule needs multiple arguments, pass them as an array:  
> `'rule' => ['arg1', 'arg2']`


**required**  _- Can not be replaced_
Set the field as required. If it's not required and it's missing, it will just be ignored. If it's set as required and is missing, the validation will fail.  
_Arguments:_ N/A  

**nullable**  _- Can not be replaced_  
Set a field as nullable. If the value is nullable and `null`, it will skip any further rules. If it's not nullable and the value is `null`, the validation will fail.  
_Arguments:_ N/A  

**as**  _- Can not be replaced_  
Set a fancy field name for the field to be used in the error templates.  
_Arguments:_ (string $fancyName)  
_Example:_ `'as' => 'A fancy name'` 

**alphanum**  
Check if a string only contains alpha numeric characters  
_Arguments:_ N/A  

**alpha**  
Check if a string only contains alpha characters  
_Arguments:_ N/A 

**email**  
Check if a string is a valid email address  
_Arguments:_ N/A 

**hex**  
Check if a string is a hex decimal value  
_Arguments:_ N/A 

**ip**  
Check if a string is a valid IP address  
_Arguments:_ N/A 

**mac**  
Check if a string is a valid MAC address  
_Arguments:_ N/A 

**url**  
Check if a string is a valid URL  
_Arguments:_ N/A 

**size**  
Check if the value is of a specific size.  
- If it's an array, it will use `count()`
- If it's a string, it will use `strlen()`
- If it's an integer or decimal, it will compare the value    

_Arguments:_ (int $size)  
_Example:_  `'size' => 10`

**minSize**  
Check if the size of the value is at least x
- If it's an array, it will use `count()`
- If it's a string, it will use `strlen()`
- If it's an integer or decimal, it will compare the value    

_Arguments:_ (int $size)  
_Example:_  `'minSize' => 5`


**maxSize**  
Check if the size of the value is at most x.  
- If it's an array, it will use `count()`
- If it's a string, it will use `strlen()`
- If it's an integer or decimal, it will compare the value   

_Arguments:_ (int $size)  
_Example:_  `'maxSize' => 30`


**in**  
Check if a value exists in a list of values. Like `in_array()`  
_Arguments:_ (array $haystack)  
_Example:_  `'in' => [1,2,3,4]`  

**notIn**  
Check if a value does not exists in a list of values. Like `in_array() === false`  
_Arguments:_ (array $haystack)  
_Example:_  `'notIn' => [1,2,3,4]`  

**same**  
Check if a value is the same as another field value  
_Arguments:_ (string $fieldname)  
_Example:_  `'same' => 'nameOfAnotherField'`  

**different**  
Check if a value is different than another field value  
_Arguments:_ (string $fieldname)  
_Example:_  `'different' => 'nameOfAnotherField'`  

**type**  
Check if a value is a specific type  
_Arguments:_ (string $type)  
_Example:_  `'type' => 'string'`  
_Possible values:_  `string`, `numeric`, `integer`, `decimal`, `array`, `boolean`  


## Rulesets

If you need/want to use the same list of rules in multiple places, it can be ineffective to manually add them in all those places.

Instead of passing around an array with the rules, you can add them as a ruleset to your main `EnsureFactory`-instance:

```php
$factory->addRuleset('myRuleset', [
    'name' => [
        'required',
        'minSize' => 5,
    ],
    'age' => [
        'required',
        'minSize' => 18,
    ],
]);
```

To use a ruleset, pass the ruleset name instead of a list of rules when creating a new `Ensure`-instance using the factory:

```php
$ensure = $factory->create('myRuleset', $_POST);
```