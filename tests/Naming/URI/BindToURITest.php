<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\URI;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidLogicalValueException;
use Rikstone\Cpe\Naming\URI\Binder;
use Rikstone\Cpe\WellFormedName;

final class BindToURITest extends TestCase
{
    private Binder $binder;

    protected function setUp(): void
    {
        $this->binder = new Binder();
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample1(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'microsoft',
            product: 'internet_explorer',
            version: '8\.0\.6001',
            update: 'beta',
            edition: new Any(),
        );

        $this->assertSame(
            'cpe:/a:microsoft:internet_explorer:8.0.6001:beta',
            $this->binder->bindToURI($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample2(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'microsoft',
            product: 'internet_explorer',
            version: '8\.*',
            update: 'sp?',
        );


        $this->assertSame(
            'cpe:/a:microsoft:internet_explorer:8.%02:sp%01',
            $this->binder->bindToURI($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample3(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'hp',
            product: 'insight_diagnostics',
            version: '7\.4\.0\.1570',
            update: new NA(),
            swEdition: 'online',
            targetSW: 'win2003',
            targetHW: 'x64',
        );


        $this->assertSame(
            'cpe:/a:hp:insight_diagnostics:7.4.0.1570:-:~~online~win2003~x64~',
            $this->binder->bindToURI($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample4(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'hp',
            product: 'openview_network_manager',
            version: '7\.51',
            targetSW: 'linux',
        );


        $this->assertSame(
            'cpe:/a:hp:openview_network_manager:7.51::~~~linux~~',
            $this->binder->bindToURI($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample5(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'foo\\\\bar',
            product: 'big\$money_manager_2010',
            swEdition: 'special',
            targetSW: 'ipod_touch',
            targetHW: '80gb',
        );

        $this->assertSame(
            'cpe:/a:foo%5cbar:big%24money_manager_2010:::~~special~ipod_touch~80gb~',
            $this->binder->bindToURI($wfn),
        );
    }
}
