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

/** @implements Rule<Class_> */
final class CoversClassPresentRule implements Rule
{
    private const string TEST_CLASS_ENDING_STRING = 'Test';

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
        return Class_::class;
    }

    /**
     * @param Class_ $node
     *
     * @return array<RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $messages = [];

        $isUnitTest = (bool) $node->extends
            && $this->isUnitTest((string) $scope->getNamespace(), (string) $node->name, $this->unitTestNamespaceContainsString);

        $hasCovers = $this->processNodeAnnotation($node, $scope) || $this->processNodeAttribute($node, $scope);

        if ($isUnitTest && !$hasCovers) {
            $messages[] = RuleErrorBuilder::message('No @covers or #[CoversClass] found in test.')
                ->build();
        }

        return $messages;
    }

    public function processNodeAnnotation(Class_ $node, Scope $scope): bool
    {
        $lines = $this->getAnnotationLines($node, $scope);

        foreach ($lines as $lineContent) {
            $lineHasCovers = (bool) preg_match('/^(?:\s*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*$/u', $lineContent, $matches);
            if ($lineHasCovers) {
                return true;
            }

            $lineHasCovers = (bool) preg_match('/^(?:\s*\/\*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*\*\/\s*$/u', $lineContent, $matches);
            if ($lineHasCovers) {
                return true;
            }
        }

        return false;
    }

    public function processNodeAttribute(Class_ $node, Scope $scope): bool
    {
        if (!$node->attrGroups) {
            return false;
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $name => $attr) {
                if ((string) $attr->name === 'PHPUnit\Framework\Attributes\CoversClass') {
                    return true;
                }

                if ((string) $attr->name === 'PHPUnit\Framework\Attributes\CoversFunction') {
                    return true;
                }

                if ((string) $attr->name === 'PHPUnit\Framework\Attributes\CoversNothing') {
                    return true;
                }
            }
        }

        return false;
    }

    /** @return array<string> */
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
                $docComment->getStartLine(),
                $docComment->getStartFilePos(),
                $docComment->getText(),
            ),
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
