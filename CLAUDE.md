# CLAUDE.md

PHPStan extension (`brainbits/phpstan-rules`, type `phpstan-extension`) with opinionated rules for PHPUnit test classes. Small library: two rules, no runtime app.

## Commands

```shell
composer install
vendor/bin/phpunit          # tests
vendor/bin/phpcs            # coding standard (brainbits/phpcs-standard)
composer phpstan            # static analysis, level max + bleedingEdge
composer check-all          # phpcs + phpstan + phpunit
```

Single test: `vendor/bin/phpunit --filter testAttributeWithUnitRule`

CI (`.github/workflows/`) runs tests on PHP 8.3 and 8.4, plus a `--prefer-lowest` run on 8.4; phpcs, phpstan and coverage run on 8.4. `composer validate --strict` must pass.

## Layout

- `src/` – rules, namespace `BrainbitsPhpStan\` (PSR-4). Note: README lists them as `Brainbits\PHPStan\Rules\…`, which is wrong; the real namespace is `BrainbitsPhpStan`.
  - `CoversClassExistsRule` – reports `#[CoversClass(...)]` attributes and `@covers` / `@coversDefaultClass` annotations that reference a non-existent class. Uses `ReflectionProvider` (autowired).
  - `CoversClassPresentRule` – requires `@covers`/`@coversDefaultClass` or `#[CoversClass]`/`#[CoversFunction]`/`#[CoversNothing]` on unit tests. A class counts as a unit test if it extends something, its namespace contains `unitTestNamespaceContainsString` (default `Unit`) and its name ends with `Test`.
- `rules.neon` – service registration (tag `phpstan.rules.rule`), the `brainbits.unitTestNamespaceContainsString` parameter and its `parametersSchema`. Auto-included via `extra.phpstan.includes` in `composer.json`. New rules and parameters must be registered here.
- `tests/` – one `RuleTestCase` per rule (classmap autoload, namespace `BrainbitsPhpStan\Tests`).
- `tests/Fixture/<RuleName>/` – PHP files analysed by the tests. `CoversClassPresentRule` fixtures under `Unit/` exercise the namespace check.

## Conventions and gotchas

- Both rules operate on `PhpParser\Node\Stmt\Class_` and check attributes and docblock annotations separately. Annotation parsing is regex-based and line-based; the same regexes exist in both rules – keep them in sync.
- Attribute names are compared as fully qualified strings (`PHPUnit\Framework\Attributes\CoversClass`), relying on PHPStan's name resolution.
- Tests assert on exact error messages **and line numbers** in fixtures. Editing a fixture shifts lines – update the expected line numbers in the test.
- Fixtures are excluded from phpcs and phpstan on purpose (they contain intentionally broken code). Don't "fix" them.
- Coding standard: `declare(strict_types=1)`, explicit `use function` imports, final classes, native type hints (one targeted `phpcs:disable` exists in `CoversClassPresentRule`).
- Supported: PHP `^8.3`, PHPStan `^2.0`, nikic/php-parser `^4.3|^5.0`. Code must work with the lowest versions too (CI `--prefer-lowest` job).
- When changing rule behaviour or parameters, update `README.md`.

## Release

semantic-release on push to `main` (`.releaserc`, tags without `v` prefix). Commit messages must follow Conventional Commits (`feat:`, `fix:`, `docs:`, `chore:`, `ci:`, `test:`) – they determine the version bump. Dependency updates come from Renovate (`renovate.json`).
