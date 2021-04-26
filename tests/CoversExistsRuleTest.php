<?php

declare(strict_types=1);

namespace BrainbitsPhpStan\Tests;

use BrainbitsPhpStan\CoversExistsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @covers \BrainbitsPhpStan\CoversExistsRule
 * @extends RuleTestCase<CoversExistsRule>
 */
final class CoversExistsRuleTest extends RuleTestCase
{
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/fixture/CoversExistsRule/fixture.php'], [
            ['Class BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Invalid does not exist.', 32],
            ['Class BrainbitsPhpStan\Tests\Fixture\CoversExistsRule\Invalid does not exist.', 33],
        ]);
    }

    protected function getRule(): Rule
    {
        $broker = $this->createBroker();

        return new CoversExistsRule($broker);
    }
}
