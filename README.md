# PHPStan Clean Test rules

[![Build](https://github.com/brainbits/phpstan-rules/actions/workflows/build.yml/badge.svg?branch=main)](https://github.com/brainbits/phpstan-rules/actions/workflows/build.yml)
[![codecov](https://codecov.io/gh/brainbits/phpstan-rules/branch/main/graph/badge.svg)](https://codecov.io/gh/brainbits/phpstan-rules)
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

All of the [rules](#rules) provided by this library are included in [`rules.neon`](rules.neon).

When you are using [`phpstan/extension-installer`](https://github.com/phpstan/extension-installer), `rules.neon` will be automatically included.

Otherwise you need to include `rules.neon` in your `phpstan.neon`:

```yaml
# phpstan.neon
includes:
    - vendor/brainbits/phpstan-rules/rules.neon
```

## Rules

This package provides the following rules for use with [`phpstan/phpstan`](https://github.com/phpstan/phpstan):
-   [`BrainbitsPhpStan\CoversClassExistsRule`](#CoversClassExistsRule)
-   [`BrainbitsPhpStan\CoversClassPresentRule`](#CoversClassPresentRule)

### `CoversClassExistsRule`

This rule checks that classes that are covered by `@covers` annotation or `#[CoversClass]` attribute exist.

### `CoversClassPresentRule`

This rule forces you to specify either a `@covers` annotation or `#[CoversClass]`, `#[CoversFunction]` or `#[CoversNothing]` attributes in unit tests.

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

#[\PHPUnit\Framework\Attributes\CoversClass(MyClass::class)]
final class MyClassTest extends \PHPUnit\Framework\TestCase {}
```

#### Defaults

-   By default, this rule treats a class as a unit test if it extends another class, its namespace contains the string `Unit` and its name ends with `Test`. The parent class itself is not checked.

#### Detecting unit tests namespace
If you want to change the namespace string check described above, you can set your own string to be checked in the `unitTestNamespaceContainsString` parameter.

```yaml
# phpstan.neon
parameters:
    brainbits:
        unitTestNamespaceContainsString: CustomTestPath
```
