<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation;

use PHPUnit\Framework\TestCase;

class Valid {
    public function validMethod() {}
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation\Valid
 */
final class JustAClass
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation\Valid
 */
final class NotATest
{
}

final class WithoutCoversTestTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation\Invalid
 */
final class WithInvalidCoversTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation\Valid
 */
final class UnitWithValidCoversTest extends TestCase
{
}
