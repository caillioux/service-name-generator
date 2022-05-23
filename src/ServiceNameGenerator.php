<?php

declare(strict_types=1);

namespace Caillioux\ServiceNameGenerator;

use Webmozart\Assert\Assert;

class ServiceNameGenerator
{
    private array $firstQualifierTerms;

    private array $nounTerms;

    private array $secondQualifierTerms;

    public function __construct(string $firstQualifierPath, string $nounPath, ?string $secondQualifierPath = null)
    {
        Assert::fileExists($firstQualifierPath);
        $firstQualifierTerms = file($firstQualifierPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->firstQualifierTerms = array_filter($firstQualifierTerms);

        Assert::fileExists($nounPath);
        $nounTerms = file($nounPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $this->nounTerms = array_filter($nounTerms);

        if ($secondQualifierPath) {
            Assert::fileExists($secondQualifierPath);
            $secondQualifierTerms = file($secondQualifierPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $this->secondQualifierTerms = array_filter($secondQualifierTerms);
        }
    }

    public function generate(string $schema = '{{firstQualifier}} {{noun}} {{secondQualifier}}'): string
    {
        $firstQualifier = $this->firstQualifierTerms[array_rand($this->firstQualifierTerms)];
        $noun = $this->nounTerms[array_rand($this->nounTerms)];
        $secondQualifier = isset($this->secondQualifierTerms) ? $this->secondQualifierTerms[array_rand(
            $this->secondQualifierTerms
        )] : '';

        return strtr(
            $schema,
            [
                '{{firstQualifier}}' => $firstQualifier,
                '{{secondQualifier}}' => $secondQualifier,
                '{{noun}}' => $noun,
            ]
        );
    }
}
