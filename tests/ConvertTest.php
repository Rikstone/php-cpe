<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Converter\Converter;
use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Cpe23;

final class ConvertTest extends TestCase
{
    public function testCpe22WillEqualAfterDoubleConvert(): void
    {
        $converter = new Converter();

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
            $cpe = Cpe22::fromString($rawCpe);
            $cpe = $converter->convert($cpe, Cpe23::class);
            $cpe = $converter->convert($cpe, Cpe22::class);

            $this->assertSame($rawCpe, (string)$cpe);
        }
    }

    public function testCpe23WillEqualAfterDoubleConvert(): void
    {
        $converter = new Converter();

        $rawCpes = [
            'cpe:2.3:a:f5:nginx:0.3.31:*:*:*:*:*:*:*',
            'cpe:2.3:a:canonical:ubuntu_advantage_desktop_daemon:1.0:*:*:*:*:*:*:*',
            'cpe:2.3:a:apache:mina_sshd:0.10.1:*:*:*:*:*:*:*',
            'cpe:2.3:a:go:ssh:0.19.0:*:*:*:*:*:*:*',
            'cpe:2.3:a:concrete5:concrete5:5.5.2.1:-:-:en-us:*:*:*:*'
        ];

        foreach ($rawCpes as $rawCpe) {
            $cpe = Cpe23::fromString($rawCpe);
            $cpe = $converter->convert($cpe, Cpe22::class);
            $cpe = $converter->convert($cpe, Cpe23::class);

            $this->assertSame($rawCpe, (string)$cpe);
        }
    }
}
