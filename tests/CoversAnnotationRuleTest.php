<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests;

use BrainbitsPhpStan\CoversAnnotationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @covers \BrainbitsPhpStan\CoversAnnotationRule
 * @extends RuleTestCase<CoversAnnotationRule>
 */
final class CoversAnnotationRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Fixture/CoversAnnotationRule/fixture-without-unit.php'], []);
        $this->analyse([__DIR__ . '/Fixture/CoversAnnotationRule/Unit/fixture-with-unit.php'], [
            ['No @covers or @coversDefaultClass found in test.', 17],
        ]);
    }

    protected function getRule(): Rule
    {
        return new CoversAnnotationRule('Unit');
    }
}
