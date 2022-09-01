<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\Unit;

use PHPUnit\Framework\TestCase;

final class JustAClass
{
}

final class NotATest
{
}

final class WithoutCoversTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\Invalid
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\Invalid::test
 */
final class WithInvalidCoversTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\JustAClass
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\JustAClass::test
 */
final class UnitWithValidCoversTest extends TestCase
{
}

/** @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\JustAClass */
final class SingleLineClassUnitWithValidCoversTest extends TestCase
{
}

/** @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\JustAClass::test */
final class SingleLineMethodUnitWithValidCoversTest extends TestCase
{
}

/** @covers \BrainbitsPhpStan\Tests\Fixture\CoversAnnotationRule\Invalid::test */
final class SingleLineMethodUnitWithInvalidCoversTest extends TestCase
{
}
