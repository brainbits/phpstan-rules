<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Unit\Annotation;

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
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Annotation\Invalid
 */
final class WithInvalidCoversTest extends TestCase
{
}

/**
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Annotation\JustAClass
 */
final class UnitWithValidCoversTest extends TestCase
{
}

/** @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Annotation\JustAClass */
final class SingleLineClassUnitWithValidCoversTest extends TestCase
{
}
