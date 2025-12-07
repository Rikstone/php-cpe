<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming\FormattedString;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Exception\InvalidFormattedStringException;
use Rikstone\Cpe\Naming\FormattedString\FormattedStringValidator;

final class FSValidatorTest extends TestCase
{
    public function testValidateEmptyString(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        FormattedStringValidator::validate('');
    }

    public function testExceptionIfFSContainLessThan11Components(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        FormattedStringValidator::validate('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*');
    }

    public function testExceptionIfFSContain11Components(): void
    {
        $this->expectNotToPerformAssertions();
        FormattedStringValidator::validate('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');
    }

    public function testExceptionIfFSContainMoreThan11Components(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        FormattedStringValidator::validate(
            'cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*:*:*:*:*:*:*',
        );
    }

    /**
     * @throws InvalidFormattedStringException
     */
    public function testURIPartComponentShouldBeValid(): void
    {
        $this->expectNotToPerformAssertions();
        FormattedStringValidator::validate('cpe:2.3:a:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');
        FormattedStringValidator::validate('cpe:2.3:h:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');
        FormattedStringValidator::validate('cpe:2.3:o:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');
    }

    public function testExceptionIfPartNotValid(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        FormattedStringValidator::validate('cpe:2.3:v:microsoft:internet_explorer:8.0.6001:beta:*:*:*:*:*:*');
    }

    public function testExceptionIfComponentEmpty(): void
    {
        $this->expectException(InvalidFormattedStringException::class);
        FormattedStringValidator::validate('cpe:2.3:v:microsoft:internet_explorer:8.0.6001::*:*:*:*:*:*');
    }
}
