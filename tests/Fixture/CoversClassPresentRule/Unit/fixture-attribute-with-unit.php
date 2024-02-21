<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\Unit\Attribute;

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
final class WithInvalidCoversClassTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassPresentRule\JustAClass::class)]
final class UnitWithValidCoversClassTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversNothing]
final class UnitWithValidCoversNothingTest extends TestCase
{
}


#[\PHPUnit\Framework\Attributes\CoversFunction]
final class UnitWithValidCoversFunctionTest extends TestCase
{
}
