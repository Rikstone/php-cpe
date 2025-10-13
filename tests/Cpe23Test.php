<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Cpe23;

final class Cpe23Test extends TestCase
{
    public function testObjectReturnCorrectFormatAfterFromStringCreated(): void
    {
        $rawCpes = [
            'cpe:2.3:o:microsoft:windows_vista:6.0:sp1:-:-:home_premium:-:x64:-',
            'cpe:2.3:a:alibaba:tengine:1.2.1:*:*:*:*:nginx:*:*',
            'cpe:2.3:a:f5:nginx:0.3.31:*:*:*:*:*:*:*',
            'cpe:2.3:a:apple:swift:4.0:*:*:*:*:ubuntu:*:* ',
            'cpe:2.3:a:canonical:ubuntu_advantage_desktop_daemon:1.0:*:*:*:*:*:*:*'
        ];

        foreach ($rawCpes as $rawCpe) {
            $this->assertSame($rawCpe, (string)(Cpe23::fromString($rawCpe)));
        }
    }

    public function testExceptionForInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString('cpe:2.3:aabbcc');
    }

    public function testExceptionForStringWithoutCpe(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString('2.3:o:microsoft:windows_vista:6.0:sp1:-:-:home_premium:-:x64:-');
    }

    public function testExceptionForEmptyPartValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString('cpe:2.3::f5:nginx:0.3.31:*:*:*:*:*:*:*');
    }
}
