<?php

declare(strict_types=1);

namespace BrainbitsPhpStan;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

use function array_merge;
use function assert;
use function preg_match;
use function preg_split;
use function sha1;
use function sprintf;

/** @implements Rule<Class_> */
final class CoversClassExistsRule implements Rule
{
    /** @var bool[] */
    private array $alreadyParsedDocComments = [];

    public function __construct(private ReflectionProvider $reflectionProvider)
    {
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     *
     * @return RuleError[] errors
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $messagesAttribute = $this->processNodeAttribute($node, $scope);
        $messagesAnnotation = $this->processNodeAnnotation($node, $scope);

        return array_merge($messagesAttribute, $messagesAnnotation);
    }

    /** @return RuleError[] errors */
    public function processNodeAttribute(Class_ $node, Scope $scope): array
    {
        $messages = [];
        if (!$node->attrGroups) {
            return [];
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $name => $attr) {
                if ((string) $attr->name !== 'PHPUnit\Framework\Attributes\CoversClass') {
                    continue;
                }

                if (!$attr->args) {
                    continue;
                }

                $arg = $attr->args[0];

                if ($arg->value instanceof ClassConstFetch) {
                    assert($arg->value->class instanceof Name);

                    $className = (string) $arg->value->class;
                    if ($this->reflectionProvider->hasClass($className)) {
                        continue;
                    }

                    $messages[] = RuleErrorBuilder::message(sprintf('Class %s does not exist.', $className))->line($attr->getStartLine())->build();

                    continue;
                }

                if ($arg->value instanceof String_) {
                    $className = (string) $arg->value->value;
                    if ($this->reflectionProvider->hasClass($className)) {
                        continue;
                    }

                    $messages[] = RuleErrorBuilder::message(sprintf('Class %s does not exist.', $className))->line($attr->getStartLine())->build();

                    continue;
                }
            }
        }

        return $messages;
    }

    /** @return RuleError[] errors */
    public function processNodeAnnotation(Class_ $node, Scope $scope): array
    {
        $messages   = [];
        $docComment = $node->getDocComment();
        if (empty($docComment)) {
            return $messages;
        }

        $hash = sha1(sprintf(
            '%s:%s:%s:%s',
            $scope->getFile(),
            $docComment->getStartLine(),
            $docComment->getStartFilePos(),
            $docComment->getText(),
        ));
        if (isset($this->alreadyParsedDocComments[$hash])) {
            return $messages;
        }

        $this->alreadyParsedDocComments[$hash] = true;

        $lines = preg_split('/\R/u', $docComment->getText());
        if ($lines === false) {
            return $messages;
        }

        foreach ($lines as $lineNumber => $lineContent) {
            $matches = [];

            if (! preg_match('/^(?:\s*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*$/u', $lineContent, $matches)) {
                if (! preg_match('/^(?:\s*\/\*\*\s*@(?:covers|coversDefaultClass)\h+)\\\\?(?<className>\w[^:\s]*)(?:::\S+)?\s*\*\/\s*$/u', $lineContent, $matches)) {
                    continue;
                }
            }

            if ($this->reflectionProvider->hasClass($matches['className'])) {
                continue;
            }

            $messages[] = RuleErrorBuilder::message(sprintf('Class %s does not exist.', $matches['className']))->line($docComment->getStartLine() + $lineNumber)->build();
        }

        return $messages;
    }
}
