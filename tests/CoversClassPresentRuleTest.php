<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests;

use BrainbitsPhpStan\CoversClassPresentRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @covers \BrainbitsPhpStan\CoversClassPresentRule
 * @extends RuleTestCase<CoversClassPresentRule>
 */
final class CoversClassPresentRuleTest extends RuleTestCase
{
    public function testAnnotationWithoutUnitRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassPresentRule/fixture-annotation-without-unit.php'], []);
    }

    public function testAnnotationWithUnitRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassPresentRule/Unit/fixture-annotation-with-unit.php'], [
            ['No @covers or #[CoversClass] found in test.', 17],
        ]);
    }

    public function testAttributeWithoutUnitRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassPresentRule/fixture-attribute-without-unit.php'], []);
    }

    public function testAttributeWithUnitRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversClassPresentRule/Unit/fixture-attribute-with-unit.php'], [
            ['No @covers or #[CoversClass] found in test.', 17],
        ]);
    }

    protected function getRule(): Rule
    {
        return new CoversClassPresentRule('Unit');
    }
}
