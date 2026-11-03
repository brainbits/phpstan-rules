# CLAUDE.md

## Projekt

`brainbits/phpstan-rules` ist eine PHPStan-Extension (Composer-Typ `phpstan-extension`) mit
zwei strikten Regeln für PHPUnit-Testklassen. Reine Library, keine Anwendung.

- PHP ^8.4. Abhängigkeiten: `phpstan/phpstan` ^2.0, `nette/utils` ^4.0 (php-parser kommt
  aus dem PHPStan-PHAR, nicht als eigene Abhängigkeit),
  Dev: PHPUnit ^13.4, `phpstan/phpstan-phpunit` ^2.0, `brainbits/phpcs-standard` ^8.
- CI (`.github/workflows/build.yml`): PHP 8.4 und 8.5, jeweils mit `lowest`- und
  `highest`-Abhängigkeiten. Bei `highest` laufen zusätzlich phpcs und PHPStan.
  `composer validate --strict` muss durchgehen.
- `code_coverage.yaml`: Coverage über Codecov (PHP 8.5, Xdebug). `codecov.yml` erlaubt beim
  Projekt-Status 1 % Toleranz.
- `release.yaml`: semantic-release bei Push nach `main` (siehe Release).

## Aufbau

- `src/` – Regeln, Namespace `BrainbitsPhpStan\` (PSR-4).
  - `CoversClassExistsRule` – meldet `#[CoversClass(...)]`-Attribute und
    `@covers`/`@coversDefaultClass`-Annotationen, die auf eine nicht existierende Klasse
    zeigen. Nutzt den `ReflectionProvider` (autowired).
  - `CoversClassPresentRule` – verlangt in Unit-Tests `@covers`/`@coversDefaultClass` oder
    `#[CoversClass]`/`#[CoversFunction]`/`#[CoversNothing]`. Unit-Test heißt: Klasse erbt
    von irgendetwas, Namespace enthält `unitTestNamespaceContainsString` (Default `Unit`),
    Klassenname endet auf `Test`.
- `rules.neon` – Service-Registrierung (Tag `phpstan.rules.rule`), Parameter
  `brainbits.unitTestNamespaceContainsString` samt `parametersSchema`. Wird über
  `extra.phpstan.includes` in `composer.json` automatisch eingebunden. Neue Regeln und
  Parameter hier registrieren.
- `tests/` – je Regel ein `RuleTestCase` (Classmap-Autoload, Namespace
  `BrainbitsPhpStan\Tests`).
- `tests/Fixture/<Regel>/` – PHP-Dateien, die die Tests analysieren. Fixtures für
  `CoversClassPresentRule` unter `Unit/` decken die Namespace-Prüfung ab.
- `phpstan.neon.dist` – analysiert `src/` und `tests/` dieses Repos (Level max,
  bleedingEdge, eigene `rules.neon`).
- `phpcs.xml.dist` – prüft `src/` und `tests/` mit dem Brainbits-Standard.

## Befehle

```bash
composer install

vendor/bin/phpunit                                  # Tests
vendor/bin/phpunit --filter testAttributeWithUnitRule   # einzelner Test
vendor/bin/phpcs                                    # Coding Standard
vendor/bin/phpstan analyse                          # Statische Analyse
```

Es gibt keine Composer-Scripts.

## Arbeitsweise

- Beide Regeln arbeiten auf `PhpParser\Node\Stmt\Class_` und prüfen Attribute und
  Docblock-Annotationen getrennt. Die Annotationen werden zeilenweise per Regex gelesen;
  dieselben Regexe stehen in beiden Regeln – synchron halten.
- Attributnamen werden als voll qualifizierte Strings verglichen
  (`PHPUnit\Framework\Attributes\CoversClass`), PHPStan löst die Namen vorher auf.
- Tests prüfen exakte Fehlermeldungen **und Zeilennummern** in den Fixtures. Wer ein
  Fixture ändert, verschiebt Zeilen – erwartete Zeilennummern im Test nachziehen.
- Fixtures sind absichtlich von phpcs und PHPStan ausgenommen (sie enthalten gewollt
  fehlerhaften Code). Nicht "reparieren".
- Code muss den Brainbits-Standard erfüllen: `declare(strict_types=1);`, importierte
  Funktionen (`use function`), finale Klassen, native Typen.
- CI testet auch `--prefer-lowest`, Versionsconstraints also nicht höher setzen als nötig,
  aber auch nicht so niedrig, dass die niedrigste Version die Tests bricht.
- Regelverhalten oder Parameter geändert → `README.md` anpassen.
- Abhängigkeiten aktualisiert Dependabot (`.github/dependabot.yml`: GitHub Actions und
  Composer).

## Release

semantic-release bei Push nach `main` (`.releaserc`, Tags ohne `v`-Präfix). Commits im Stil
Conventional Commits (`feat:`, `fix:`, `fix(deps):`, `docs:`, `chore:`, `ci:`, `test:`) –
sie bestimmen die Versionsnummer. Entwicklungsbranch ist `main`; `master` ist veraltet.
