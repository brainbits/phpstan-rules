<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use Brainbits\PHPStan\Rules\CoversAnnotationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends \PHPStan\Testing\RuleTestCase<CoversAnnotationRule>
 */
final class CoversAnnotationRuleTest extends RuleTestCase
{
    private const ERROR_MESSAGE = 'No @covers or @coversDefaultClass found in test.';

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/data/unit/unit-without-covers.php'], [
            [self::ERROR_MESSAGE, 9],
        ]);

        $this->analyse([__DIR__ . '/data/unit/unit-with-covers.php'], []);
        $this->analyse([__DIR__ . '/data/without-covers.php'], []);
    }

    protected function getRule(): Rule
    {
        return new CoversAnnotationRule('Unit');
    }
}
