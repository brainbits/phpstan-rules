<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversExistsRule;

use PHPUnit\Framework\TestCase;

class Valid {
    public function validMethod() {}
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Valid
 */
final class JustAClass
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Valid
 */
final class NotATest
{
}

final class WithoutCoversTestTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Invalid
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Invalid::invalidMethod
 */
final class WithInvalidCoversTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Valid
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Valid::validMethod
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Valid::invalidMethod
 */
final class UnitWithValidCoversTest extends TestCase
{
}
