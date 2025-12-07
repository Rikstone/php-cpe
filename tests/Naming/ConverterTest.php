<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Exception\InvalidFormattedStringException;
use Rikstone\Cpe\Exception\InvalidLogicalValueException;
use Rikstone\Cpe\Exception\InvalidURIException;
use Rikstone\Cpe\Naming\FormattedString\Binder as FsBinder;
use Rikstone\Cpe\Naming\FormattedString\Unbinder as FsUnbinder;
use Rikstone\Cpe\Naming\URI\Binder as UriBinder;
use Rikstone\Cpe\Naming\URI\Unbinder as UriUnbinder;

final class ConverterTest extends TestCase
{
    private FsBinder $fsBinder;

    private FsUnbinder $fsUnbinder;
    private UriBinder $uriBinder;
    private UriUnbinder $uriUnbinder;

    protected function setUp(): void
    {
        $this->uriUnbinder = new UriUnbinder();
        $this->uriBinder = new UriBinder();
        $this->fsBinder = new FsBinder();
        $this->fsUnbinder = new FsUnbinder();
    }


    /**
     * @throws InvalidURIException
     * @throws InvalidLogicalValueException
     */
    public function testConvertURIToFS(): void
    {
        $wfn = $this->uriUnbinder->unbindURI('cpe:/a:microsoft:internet_explorer:8.0.6001:beta');

        $this->assertSame(
            'cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*',
            $this->fsBinder->bindToFS($wfn),
        );
    }


    /**
     * @throws InvalidLogicalValueException
     * @throws InvalidFormattedStringException
     */
    public function testConvertFSToURI(): void
    {
        $wfn = $this->fsUnbinder->unbindFS('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');

        $this->assertSame(
            'cpe:/a:microsoft:internet_explorer:8.0.6001:beta',
            $this->uriBinder->bindToURI($wfn),
        );
    }

    /**
     * @throws InvalidLogicalValueException
     * @throws InvalidFormattedStringException
     * @throws InvalidURIException
     */
    public function testEqualsAfterDoubleConvertFromFS(): void
    {
        $originalFS = 'cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*';
        $wfn = $this->fsUnbinder->unbindFS($originalFS);
        $uri = $this->uriBinder->bindToURI($wfn);
        $wfn = $this->uriUnbinder->unbindURI($uri);
        $fs = $this->fsBinder->bindToFS($wfn);

        $this->assertSame($originalFS, $fs);
    }

    /**
     * @throws InvalidLogicalValueException
     * @throws InvalidFormattedStringException
     * @throws InvalidURIException
     */
    public function testEqualsAfterDoubleConvertFromURI(): void
    {
        $originalURI = 'cpe:/a:microsoft:internet_explorer:8.0.6001:beta';
        $wfn = $this->uriUnbinder->unbindURI($originalURI);
        $fs = $this->fsBinder->bindToFS($wfn);
        $wfn = $this->fsUnbinder->unbindFS($fs);
        $uri = $this->uriBinder->bindToURI($wfn);

        $this->assertSame($originalURI, $uri);
    }
}
