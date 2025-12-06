<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\WellFormedName;

final class WellFormedNameTest extends TestCase
{
    public function testObjectOnlyWithPartCreation(): void
    {
        $this->assertInstanceOf(WellFormedName::class, new WellFormedName(Part::A));
    }

    public function testPropertiesOfObject(): void
    {
        $wfn = new WellFormedName(
            Part::A,
            'hp',
            'insight_diagnostics',
            '7\\.4\\.0\\.1570',
            'beta',
            new Any(),
            'online',
            'windows_2003',
            'x32',
            'en-US',
            'some information',
        );

        $this->assertSame(Part::A, $wfn->part);
        $this->assertSame('hp', $wfn->vendor);
        $this->assertSame('insight_diagnostics', $wfn->product);
        $this->assertSame('7\\.4\\.0\\.1570', $wfn->version);
        $this->assertSame('beta', $wfn->update);
        $this->assertInstanceOf(Any::class, $wfn->edition);
        $this->assertSame('online', $wfn->swEdition);
        $this->assertSame('windows_2003', $wfn->targetSW);
        $this->assertSame('x32', $wfn->targetHW);
        $this->assertSame('en-US', $wfn->language);
        $this->assertSame('some information', $wfn->other);
    }
}
