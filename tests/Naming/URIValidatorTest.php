<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Rikstone\Cpe\Exception\InvalidURIException;
use Rikstone\Cpe\Naming\URI\URIValidator;

final class URIValidatorTest extends TestCase
{
    public function testValidateEmptyString(): void
    {
        $this->expectException(InvalidURIException::class);
        URIValidator::validate('');
    }

    public function testURIContainMoreThan7Components(): void
    {
        $this->expectException(InvalidURIException::class);
        URIValidator::validate('cpe:/a:test:test:test:test:test:test:test');
    }

    /**
     * @throws InvalidURIException
     */
    public function testURIContain7Components(): void
    {
        $this->expectNotToPerformAssertions();
        URIValidator::validate('cpe:/a:test:test:test:test:test:test');
    }

    /**
     * @throws InvalidURIException
     */
    public function testURIContainLessThan7Components(): void
    {
        $this->expectNotToPerformAssertions();
        URIValidator::validate('cpe:/a:test:test');
    }

    public function testURIMustContainPartComponent(): void
    {
        $this->expectException(InvalidURIException::class);
        URIValidator::validate('cpe:/');
    }

    /**
     * @throws InvalidURIException
     */
    public function testURIPartComponentShouldBeValid(): void
    {
        $this->expectNotToPerformAssertions();
        URIValidator::validate('cpe:/a');
        URIValidator::validate('cpe:/h');
        URIValidator::validate('cpe:/o');
    }

    public function testExceptionIfPartNotValid(): void
    {
        $this->expectException(InvalidURIException::class);
        URIValidator::validate('cpe:/v');
    }
}
