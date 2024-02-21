<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests;

use BrainbitsPhpStan\CoversClassExistsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @covers \BrainbitsPhpStan\CoversClassExistsRule
 * @extends RuleTestCase<CoversClassExistsRule>
 */
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
        $broker = $this->createBroker();

        return new CoversClassExistsRule($broker);
    }
}
