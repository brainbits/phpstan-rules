<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests;

use BrainbitsPhpStan\CoversClassExistsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/** @extends RuleTestCase<CoversClassExistsRule> */
#[CoversClass(CoversClassExistsRule::class)]
final class CoversClassExistsRuleTest extends RuleTestCase
{
    public function testAttributeRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassExistsRule/fixture-attribute.php'], [
            ['Class BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\Invalid does not exist.', 37],
            ['Class BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Attribute\Invalid does not exist.', 42],
        ]);
    }

    public function testAnnotationRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassExistsRule/fixture-annotation.php'], [
            ['Class BrainbitsPhpStan\Tests\Fixture\CoversClassExistsRule\Annotation\Invalid does not exist.', 32],
        ]);
    }

    protected function getRule(): Rule
    {
        $broker = self::createReflectionProvider();

        return new CoversClassExistsRule($broker);
    }
}
