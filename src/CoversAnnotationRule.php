<?php

declare(strict_types=1);

namespace BrainbitsPhpStan;

use Nette\Utils\Strings;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

use function array_key_exists;
use function preg_match;
use function preg_split;
use function sha1;
use function sprintf;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint

/**
 * @implements Rule<Node>
 */
final class CoversAnnotationRule implements Rule
{
    private const TEST_CLASS_ENDING_STRING = 'Test';

    /** @var string */
    private $unitTestNamespaceContainsString;

    /** @var array<string, bool> */
    private $alreadyParsedDocComments = [];

    public function __construct(string $unitTestNamespaceContainsString)
    {
        $this->unitTestNamespaceContainsString = $unitTestNamespaceContainsString;
    }

    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @return array<RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $messages = [];
        $lines = $this->getAnnotationLines($node, $scope);

        $isUnitTest = $node instanceof Class_
            && (bool) $node->extends
            && $this->isUnitTest((string) $scope->getNamespace(), (string) $node->name, $this->unitTestNamespaceContainsString);

        $hasCovers = false;
        foreach ($lines as $lineContent) {
            $lineHasCovers = (bool) preg_match('/^(?:\s*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*$/u', $lineContent, $matches);
            if ($lineHasCovers) {
                $hasCovers = true;
                break;
            }

            $lineHasCovers = (bool) preg_match('/^(?:\s*\/\*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*\*\/\s*$/u', $lineContent, $matches);
            if ($lineHasCovers) {
                $hasCovers = true;
                break;
            }
        }

        if ($isUnitTest && !$hasCovers) {
            $messages[] = RuleErrorBuilder::message('No @covers or @coversDefaultClass found in test.')
                ->build();
        }

        return $messages;
    }

    /**
     * @return array<string>
     */
    private function getAnnotationLines(Node $node, Scope $scope): array
    {
        $docComment = $node->getDocComment();
        if (!$docComment instanceof Doc) {
            return [];
        }

        $hash = sha1(
            sprintf(
                '%s:%s:%s:%s',
                $scope->getFile(),
                $docComment->getLine(),
                $docComment->getFilePos(),
                $docComment->getText()
            )
        );

        if (array_key_exists($hash, $this->alreadyParsedDocComments)) {
            return [];
        }

        $this->alreadyParsedDocComments[$hash] = true;

        $lines = [];
        foreach ((array) preg_split('/\R/u', $docComment->getText()) as $line) {
            $lines[] = (string) $line;
        }

        return $lines;
    }

    private function isUnitTest(string $namespace, string $className, string $unitTestNamespacePattern): bool
    {
        return Strings::contains($namespace, $unitTestNamespacePattern)
            && Strings::endsWith($className, self::TEST_CLASS_ENDING_STRING);
    }
}
