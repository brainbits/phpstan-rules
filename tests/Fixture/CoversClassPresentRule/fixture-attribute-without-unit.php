<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Attribute;

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

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Invalid::class)]
#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Invalid')]
#[\PHPUnit\Framework\Attributes\CoversNothing]
final class WithInvalidCoversTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\JustAClass::class)]
#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\JustAClass')]
#[\PHPUnit\Framework\Attributes\CoversNothing]
final class UnitWithValidCoversTest extends TestCase
{
}
