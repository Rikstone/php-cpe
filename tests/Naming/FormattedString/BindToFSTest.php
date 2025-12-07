<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\FormattedString;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidLogicalValueException;
use Rikstone\Cpe\Naming\FormattedString\Binder;
use Rikstone\Cpe\WellFormedName;

final class BindToFSTest extends TestCase
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
            'cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*',
            $this->binder->bindToFS($wfn),
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
            edition: new Any(),
        );

        $this->assertSame(
            'cpe:2.3:a:microsoft:internet_explorer:8.*:sp?:*:*:*:*:*:*',
            $this->binder->bindToFS($wfn),
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
            product: 'insight',
            version: '7\.4\.0\.1570',
            update: new NA(),
            swEdition: 'online',
            targetSW: 'win2003',
            targetHW: 'x64',
        );

        $this->assertSame(
            'cpe:2.3:a:hp:insight:7.4.0.1570:-:*:*:online:win2003:x64:*',
            $this->binder->bindToFS($wfn),
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
            'cpe:2.3:a:hp:openview_network_manager:7.51:*:*:*:*:linux:*:*',
            $this->binder->bindToFS($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     */
    public function testExample5(): void
    {
        $wfn = new WellFormedName(
            part: Part::A,
            vendor: 'foo\\bar',
            product: 'big\$money_2010',
            swEdition: 'special',
            targetSW: 'ipod_touch',
            targetHW: '80gb',
        );

        $this->assertSame(
            'cpe:2.3:a:foo\\bar:big\$money_2010:*:*:*:*:special:ipod_touch:80gb:*',
            $this->binder->bindToFS($wfn),
        );
    }
}
