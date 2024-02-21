<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Annotation;

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
 * @Covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Invalid
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Invalid
 */
final class WithInvalidCoversTest extends TestCase
{
}

/**
 * @Covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\JustAClass
 * @covers \BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\JustAClass
 */
final class UnitWithValidCoversTest extends TestCase
{
}
