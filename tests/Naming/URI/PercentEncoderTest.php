<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\URI;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Naming\URI\PercentEncoder;
use Rikstone\Cpe\Naming\URI\PercentMap;

final class PercentEncoderTest extends TestCase
{
    public function testEncode(): void
    {
        foreach (PercentMap::getEncodeMap() as $symbol => $value) {
            $this->assertSame($value, PercentEncoder::encode($symbol));
        }
    }

    public function testEncodeUnmappedCharacters(): void
    {
        $symbols = ['a', 'b', '1', '9', '_', ' '];

        foreach ($symbols as $char) {
            $this->assertSame($char, PercentEncoder::encode($char), "Failed for unmapped char: {$char}");
        }
    }
}
