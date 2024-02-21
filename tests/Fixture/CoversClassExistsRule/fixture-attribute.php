<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute;

use PHPUnit\Framework\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\FirstValid::class)]
class FirstValid {
    public function validMethod() {}
}

#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\SecondValid')]
class SecondValid {
    public function validMethod() {}
}

final class JustAClass
{
}

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\FirstValid::class)]
final class FirstNotATest
{
}

#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\SecondValid')]
final class SecondNotATest
{
}

final class WithoutCoversTestTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\Invalid::class)]
final class FirstWithInvalidCoversTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\Invalid')]
final class SecondWithInvalidCoversTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass(\BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\FirstValid::class)]
final class FirstUnitWithValidCoversTest extends TestCase
{
}

#[\PHPUnit\Framework\Attributes\CoversClass('BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\SecondValid')]
final class SecondUnitWithValidCoversTest extends TestCase
{
}
