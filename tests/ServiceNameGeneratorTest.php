<?php

declare(strict_types=1);

use Caillioux\ServiceNameGenerator\ServiceNameGenerator;
use PHPUnit\Framework\TestCase;

final class ServiceNameGeneratorTest extends TestCase
{
    private ServiceNameGenerator $generator;

    private array $results;

    private array $firstQualifiers;

    private array $nouns;

    private array $nounFirstQualifierTerms;

    protected function setUp(): void
    {
        $this->generator = new ServiceNameGenerator(
            __DIR__ . '/resources/first_qualifiers.txt',
            __DIR__ . '/resources/nouns.txt',
            __DIR__ . '/resources/second_qualifiers.txt'
        );

        $this->nouns = array_filter(file(__DIR__ . '/resources/nouns.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));
        $this->firstQualifiers = array_filter(file(__DIR__ . '/resources/first_qualifiers.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES));

        $this->results = file(__DIR__ . '/resources/results.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $nounFirstQualifierTerms = [];
        foreach ($this->nouns as $noun) {
            foreach ($this->firstQualifiers as $qualifier) {
                $nounFirstQualifierTerms[] = $noun . ' ' . $qualifier;
            }
        }
        $this->nounFirstQualifierTerms = $nounFirstQualifierTerms;
    }

    /**
     * @covers \Caillioux\ServiceNameGenerator\ServiceNameGenerator::generate
     */
    public function testGenerateBasicNames()
    {
        // 3 times
        $name = $this->generator->generate();
        $this->assertContains($name, $this->results);

        $name = $this->generator->generate();
        $this->assertContains($name, $this->results);

        $name = $this->generator->generate();
        $this->assertContains($name, $this->results);
    }

    /**
     * @covers \Caillioux\ServiceNameGenerator\ServiceNameGenerator::generate
     */
    public function testGenerateWithCustomSchema()
    {
        // 1 symbol schema
        $name = $this->generator->generate('{{noun}}');
        $this->assertContains($name, $this->nouns);

        // 2 symbol schema
        $name = $this->generator->generate('{{noun}} {{firstQualifier}}');
        $this->assertContains($name, $this->nounFirstQualifierTerms);

        $name = $this->generator->generate();
        $this->assertContains($name, $this->results);
    }

    /**
     * @covers \Caillioux\ServiceNameGenerator\ServiceNameGenerator::generate
     */
    public function testCreateGeneratorWithTwoFiles()
    {
        $generator = new ServiceNameGenerator(
            __DIR__ . '/resources/first_qualifiers.txt',
            __DIR__ . '/resources/nouns.txt'
        );

        $name = $generator->generate('{{noun}} {{firstQualifier}}');
        $this->assertContains($name, $this->nounFirstQualifierTerms);
    }
}
