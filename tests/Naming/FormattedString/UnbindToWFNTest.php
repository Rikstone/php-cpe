<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\FormattedString;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Exception\InvalidFormattedStringException;
use Rikstone\Cpe\Naming\FormattedString\Unbinder;
use Rikstone\Cpe\WellFormedName;

final class UnbindToWFNTest extends TestCase
{
    private Unbinder $unbinder;

    protected function setUp(): void
    {
        $this->unbinder = new Unbinder();
    }

    public function testUnbindToWFN(): void
    {
        $this->assertInstanceOf(
            WellFormedName::class,
            $this->unbinder->unbindFS('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*'),
        );
    }

    public function testUnbinderMustWorkOnlyWithValidatedURI(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        $this->unbinder->unbindFS('');
    }

    public function testExample1(): void
    {
        $wfn = $this->unbinder->unbindFS('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('microsoft', $wfn->vendor);
        $this->assertSame('internet_explorer', $wfn->product);
        $this->assertSame('8\.0\.6001', $wfn->version);
        $this->assertSame('beta', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
        $this->assertInstanceOf(Any::class, $wfn->swEdition);
        $this->assertInstanceOf(Any::class, $wfn->targetSW);
        $this->assertInstanceOf(Any::class, $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
    }

    public function testExample2(): void
    {
        $wfn = $this->unbinder->unbindFS('cpe:2.3:a:microsoft:internet_explorer:8.*:sp?:*:*:*:*:*:*');

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('microsoft', $wfn->vendor);
        $this->assertSame('internet_explorer', $wfn->product);
        $this->assertSame('8\.*', $wfn->version);
        $this->assertSame('sp?', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
        $this->assertInstanceOf(Any::class, $wfn->swEdition);
        $this->assertInstanceOf(Any::class, $wfn->targetSW);
        $this->assertInstanceOf(Any::class, $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
    }

    public function testExample3(): void
    {
        $wfn = $this->unbinder->unbindFS('cpe:2.3:a:hp:insight_diagnostics:7.4.0.1570:-:*:*:online:win2003:x64:*');

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('hp', $wfn->vendor);
        $this->assertSame('insight_diagnostics', $wfn->product);
        $this->assertSame('7\.4\.0\.1570', $wfn->version);
        $this->assertInstanceOf(NA::class, $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
        $this->assertSame('online', $wfn->swEdition);
        $this->assertSame('win2003', $wfn->targetSW);
        $this->assertSame('x64', $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
    }

    public function testExample4(): void
    {
        $wfn = $this->unbinder->unbindFS('cpe:2.3:a:foo\\bar:big\$money:2010:*:*:*:special:ipod_touch:80gb:*');

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('foo\\bar', $wfn->vendor);
        $this->assertSame('big\$money', $wfn->product);
        $this->assertSame('2010', $wfn->version);
        $this->assertInstanceOf(Any::class, $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
        $this->assertSame('special', $wfn->swEdition);
        $this->assertSame('ipod_touch', $wfn->targetSW);
        $this->assertSame('80gb', $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
    }
}
