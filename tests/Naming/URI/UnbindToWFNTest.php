<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\URI;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Exception\InvalidURIException;
use Rikstone\Cpe\Naming\URI\Unbinder;
use Rikstone\Cpe\WellFormedName;

final class UnbindToWFNTest extends TestCase
{
    private Unbinder $unbinder;

    protected function setUp(): void
    {
        $this->unbinder = new Unbinder();
    }

    /**
     * @throws InvalidURIException
     */
    public function testUnbindToWFN(): void
    {
        $this->assertInstanceOf(
            WellFormedName::class,
            $this->unbinder->unbindURI('cpe:/a'),
        );
    }

    public function testUnbinderMustWorkOnlyWithValidatedURI(): void
    {
        $this->expectException(InvalidURIException::class);
        $this->unbinder->unbindURI('');
    }

    /**
     * @throws InvalidURIException
     */
    public function testExample1(): void
    {
        $wfn = $this->unbinder->unbindURI('cpe:/a:microsoft:internet_explorer:8.0.6001:beta');

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('microsoft', $wfn->vendor);
        $this->assertSame('internet_explorer', $wfn->product);
        $this->assertSame('8\.0\.6001', $wfn->version);
        $this->assertSame('beta', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    /**
     * @throws InvalidURIException
     */
    public function testExample2(): void
    {
        $wfn = $this->unbinder->unbindURI(
            'cpe:/a:microsoft:internet_explorer:8.%2a:sp%3f',
        );

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('microsoft', $wfn->vendor);
        $this->assertSame('internet_explorer', $wfn->product);
        $this->assertSame('8\.\*', $wfn->version);
        $this->assertSame('sp\?', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    public function testExample3(): void
    {
        $wfn = $this->unbinder->unbindURI(
            'cpe:/a:microsoft:internet_explorer:8.%02:sp%01',
        );

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('microsoft', $wfn->vendor);
        $this->assertSame('internet_explorer', $wfn->product);
        $this->assertSame('8\.*', $wfn->version);
        $this->assertSame('sp?', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    public function testExample4(): void
    {
        $wfn = $this->unbinder->unbindURI(
            'cpe:/a:hp:insight_diagnostics:7.4.0.1570::~~online~win2003~x64~',
        );

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('hp', $wfn->vendor);
        $this->assertSame('insight_diagnostics', $wfn->product);
        $this->assertSame('7\.4\.0\.1570', $wfn->version);
        $this->assertInstanceOf(Any::class, $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertSame('online', $wfn->swEdition);
        $this->assertSame('win2003', $wfn->targetSW);
        $this->assertSame('x64', $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    /**
     * @throws InvalidURIException
     */
    public function testExample5(): void
    {
        $wfn = $this->unbinder->unbindURI(
            'cpe:/a:hp:openview_network_manager:7.51:-:~~~linux~~',
        );

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('hp', $wfn->vendor);
        $this->assertSame('openview_network_manager', $wfn->product);
        $this->assertSame('7\.51', $wfn->version);
        $this->assertInstanceOf(NA::class, $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->swEdition);
        $this->assertSame('linux', $wfn->targetSW);
        $this->assertInstanceOf(Any::class, $wfn->targetHW);
        $this->assertInstanceOf(Any::class, $wfn->other);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    public function testExample6(): void
    {
        $this->expectException(InvalidURIException::class);

        $this->unbinder->unbindURI(
            'cpe:/a:foo%5cbar:big%24money_2010%07:::~~special~ipod_touch~80gb~',
        );
    }

    /**
     * @throws InvalidURIException
     */
    public function testExample7(): void
    {
        $wfn = $this->unbinder->unbindURI(
            'cpe:/a:foo~bar:big%7emoney_2010',
        );

        $this->assertSame('a', $wfn->part->value);
        $this->assertSame('foo\~bar', $wfn->vendor);
        $this->assertSame('big\~money_2010', $wfn->product);
        $this->assertInstanceOf(Any::class, $wfn->version);
        $this->assertInstanceOf(Any::class, $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertInstanceOf(Any::class, $wfn->language);
    }

    public function testExample8(): void
    {
        $this->expectException(InvalidURIException::class);

        $this->unbinder->unbindURI(
            'cpe:/a:foo:bar:12.%02.1234',
        );
    }
}
