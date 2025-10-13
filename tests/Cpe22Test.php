<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Cpe22;

final class Cpe22Test extends TestCase
{
    public function testObjectReturnCorrectFormatAfterFromStringCreated(): void
    {
        $rawCpes = [
            'cpe:/o:microsoft:windows_2000',
            'cpe:/o:microsoft:windows_xp::sp2:pro',
            'cpe:/o:microsoft:windows_2000:-:-:~~advanced_server~~~',
            'cpe:/o:redhat:enterprise_linux:3::as',
            'cpe:/a:apache:httpd:2.0.52',
            'cpe:/a:microsoft:ie:6.0',
            'cpe:/h:cisco:router:3825',
            'cpe:/h:dell:inspiron:8500',
            'cpe:/o:microsoft:windows_2000:-:-:~~server~~~',
            'cpe:/o:microsoft:windows_2000:-:sp2',
            'cpe:/h:emc:vmware_esx:2.5'
        ];

        foreach ($rawCpes as $rawCpe) {
            $this->assertSame($rawCpe, (string)(Cpe22::fromString($rawCpe)));
        }
    }

    public function testExceptionForInvalidString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString('cpe:/aabbcc');
    }

    public function testExceptionForStringWithoutCpe(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString(':/o:microsoft:windows_2000');
    }

    public function testExceptionForEmptyPartValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Cpe22::fromString('cpe:/:microsoft:windows_2000');
    }
}
