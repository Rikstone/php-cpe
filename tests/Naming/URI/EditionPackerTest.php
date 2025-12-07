<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\URI;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Naming\URI\EditionPacker;

final class EditionPackerTest extends TestCase
{
    public function testPackEmptyValues(): void
    {
        $this->assertSame(
            '',
            EditionPacker::pack(
                '',
                '',
                '',
                '',
                '',
            ),
        );
    }

    public function testPack(): void
    {
        $this->assertSame(
            '~~online~win2003~x64~',
            EditionPacker::pack(
                '',
                'online',
                'win2003',
                'x64',
                '',
            ),
        );
    }
}
