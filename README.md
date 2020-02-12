# PHPStan Clean Test rules

![Continuous Integration](https://github.com/brainbits/phpstan-rules/workflows/continuous-integration/badge.svg?event=push)
[![Coverage Status](https://coveralls.io/repos/github/brainbits/phpstan-rules/badge.svg?branch=master)](https://coveralls.io/github/brainbits/phpstan-rules?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/881bcaeeaa2a45f59b0a77680f15fafe)](https://www.codacy.com/manual/brainbits/phpstan-rules?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=brainbits/phpstan-rules&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/brainbits/phpstan-rules/version)](https://packagist.org/packages/brainbits/phpstan-rules)
[![License](https://poser.pugx.org/brainbits/phpstan-rules/license)](https://packagist.org/packages/brainbits/phpstan-rules)

-   [PHPStan](https://github.com/phpstan/phpstan)
-   [PHPStan-PHPUnit extension](https://github.com/phpstan/phpstan-phpunit)

This extension provides highly opinionated and strict rules for test cases for the PHPStan static analysis tool.

## Installation

Run

```shell
$ composer require --dev brainbits/phpstan-rules
```

## Usage

All of the [rules](https://github.com/brainbits/phpstan-rules#rules) provided by this library are included in [`rules.neon`](rules.neon).

When you are using [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer), `rules.neon` will be automatically included.

Otherwise you need to include `rules.neon` in your `phpstan.neon`:

```yaml
# phpstan.neon
includes:
    - vendor/brainbits/phpstan-rules/rules.neon
```

## Rules

This package provides the following rules for use with [`phpstan/phpstan`](https://github.com/phpstan/phpstan):
-   [`Brainbits\PHPStan\Rules\CoversAnnotationRule`](#coversannotationrule)

### `CoversAnnotationRule`

This rule forces you to specify a @covers or @coversDefaultClass annotation in unit tests (default: `PHPUnit\Framework\TestCase`).

**Why:**
1. It prevents code coverage sums to show higher values than expected. 

:x:

```php
// tests/ExampleTestCase/Unit/MyInvalidClassTest.php
namespace ExampleTestCase\Unit;

final class MyInvalidClassTest extends \PHPUnit\Framework\TestCase {}
```
<br />

:white_check_mark:

```php
// tests/ExampleTestCase/Unit/MyClassTest.php
namespace ExampleTestCase\Unit;
/**
 * @covers MyClass
 */
final class MyClassTest extends \PHPUnit\Framework\TestCase {}
```

#### Defaults

-   By default, this rule detects unit tests by checking the namespace (it must contain the string `Unit`) and the class name ending (it must end with the string `Test`).

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter.

```yaml
# phpstan.neon
parameters:
    brainbits:
        unitTestNamespaceContainsString: CustomTestPath
```
