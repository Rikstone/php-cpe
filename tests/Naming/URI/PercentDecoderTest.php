<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\URI;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Naming\URI\PercentDecoder;

final class PercentDecoderTest extends TestCase
{
    public function testEmptyStringReturnsAny(): void
    {
        $result = PercentDecoder::decode("");
        $this->assertInstanceOf(Any::class, $result);
    }

    public function testHyphenReturnsNA(): void
    {
        $result = PercentDecoder::decode("-");
        $this->assertInstanceOf(NA::class, $result);
    }

    public function testPlainCharactersRemain(): void
    {
        $this->assertSame("abc", PercentDecoder::decode("abc"));
    }

    public function testDotHyphenTildeAreEscaped(): void
    {
        $this->assertSame("\\.\\-\\~", PercentDecoder::decode(".-~"));
    }

    public function testSimplePercentSequence(): void
    {
        $this->assertSame("\\!", PercentDecoder::decode("%21"));
        $this->assertSame("\\\\", PercentDecoder::decode("%5c"));
    }

    public function testUnknownFormThrows(): void
    {
        $this->expectException(Exception::class);
        PercentDecoder::decode("%ZZ");
    }

    public function testIncompletePercentSequenceThrows(): void
    {
        $this->expectException(Exception::class);
        PercentDecoder::decode("%2");
    }

    #[DataProvider('mappingProvider')]
    public function testAllMappings(string $encoded, string $expected): void
    {
        $this->assertSame($expected, PercentDecoder::decode($encoded));
    }

    /**
     * @return array<array{string, string}>
     */
    public static function mappingProvider(): array
    {
        return [
            ["%21", "\\!"],
            ["%22", "\\\""],
            ["%23", "\\#"],
            ["%24", "\\$"],
            ["%25", "\\%"],
            ["%26", "\\&"],
            ["%27", "\\'"],
            ["%28", "\\("],
            ["%29", "\\)"],
            ["%2a", "\\*"],
            ["%2b", "\\+"],
            ["%2c", "\\,"],
            ["%2f", "\\/"],
            ["%3a", "\\:"],
            ["%3b", "\\;"],
            ["%3c", "\\<"],
            ["%3d", "\\="],
            ["%3e", "\\>"],
            ["%3f", "\\?"],
            ["%40", "\\@"],
            ["%5b", "\\["],
            ["%5c", "\\\\"],
            ["%5d", "\\]"],
            ["%5e", "\\^"],
            ["%60", "\\`"],
            ["%7b", "\\{"],
            ["%7c", "\\|"],
            ["%7d", "\\}"],
            ["%7e", "\\~"],
        ];
    }

    public function testPercent01ValidPositions(): void
    {
        $this->assertSame("?", PercentDecoder::decode("%01"));

        $this->assertSame("x?", PercentDecoder::decode("x%01"));

        $this->assertSame("??", PercentDecoder::decode("%01%01"));

        $this->expectException(Exception::class);
        PercentDecoder::decode("a%01a");
    }

    public function testPercent01InvalidCaseThrows(): void
    {
        $this->expectException(Exception::class);
        PercentDecoder::decode("x%01y");
    }

    public function testPercent02AllowedOnlyAtStartOrEnd(): void
    {
        $this->assertSame("*", PercentDecoder::decode("%02"));
        $this->assertSame("a*", PercentDecoder::decode("a%02"));

        $this->expectException(Exception::class);
        PercentDecoder::decode("a%02b");
    }
}