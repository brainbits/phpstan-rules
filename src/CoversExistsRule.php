<?php

declare(strict_types=1);

namespace BrainbitsPhpStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

use function preg_match;
use function preg_split;
use function sha1;
use function sprintf;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint

/**
 * @implements Rule<Node>
 */
final class CoversExistsRule implements Rule
{
    /** @var Broker */
    private $broker;
    /** @var bool[] */
    private $alreadyParsedDocComments = [];

    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }

    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @return RuleError[] errors
     */
    public function processNode(Node $node, Scope $scope): array
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
            $docComment->getText()
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

            if ($this->broker->hasClass($matches['className'])) {
                continue;
            }

            $messages[] = RuleErrorBuilder::message(sprintf('Class %s does not exist.', $matches['className']))->line($docComment->getStartLine() + $lineNumber)->build();
        }

        return $messages;
    }
}
