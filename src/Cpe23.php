<?php

declare(strict_types=1);

namespace Rikstone\Cpe;

use InvalidArgumentException;

class Cpe23 implements Cpe
{
    private Part $part;
    private string $vendor = '*';
    private string $product = '*';
    private string $version = '*';
    private string $update = '*';
    private string $edition = '*';
    private string $language = '*';
    private string $swEdition = '*';
    private string $targetSw = '*';
    private string $targetHw = '*';
    private string $other = '*';

    public function getPart(): Part
    {
        return $this->part;
    }

    public function setPart(Part $part): Cpe23
    {
        $this->part = $part;
        return $this;
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function setVendor(string $vendor): Cpe23
    {
        $this->vendor = $this->normalizeString($vendor);
        return $this;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function setProduct(string $product): Cpe23
    {
        $this->product = $this->normalizeString($product);
        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): Cpe23
    {
        $this->version = $version;
        return $this;
    }

    public function getUpdate(): string
    {
        return $this->update;
    }

    public function setUpdate(string $update): Cpe23
    {
        $this->update = $update;
        return $this;
    }

    public function getEdition(): string
    {
        return $this->edition;
    }

    public function setEdition(string $edition): Cpe23
    {
        $this->edition = $edition;
        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): Cpe23
    {
        $this->language = $language;
        return $this;
    }

    public function getSwEdition(): string
    {
        return $this->swEdition;
    }

    public function setSwEdition(string $swEdition): Cpe23
    {
        $this->swEdition = $swEdition;
        return $this;
    }

    public function getTargetSw(): string
    {
        return $this->targetSw;
    }

    public function setTargetSw(string $targetSw): Cpe23
    {
        $this->targetSw = $targetSw;
        return $this;
    }

    public function getTargetHw(): string
    {
        return $this->targetHw;
    }

    public function setTargetHw(string $targetHw): Cpe23
    {
        $this->targetHw = $targetHw;
        return $this;
    }

    public function getOther(): string
    {
        return $this->other;
    }

    public function setOther(string $other): Cpe23
    {
        $this->other = $other;
        return $this;
    }

    private function normalizeString(string $value): string
    {
        return mb_strtolower(str_replace(' ', '_', $value));
    }

    public static function fromString(string $cpe): self
    {
        if (!str_starts_with($cpe, 'cpe:2.3:')) {
            throw new InvalidArgumentException("Invalid CPE 2.3 string: $cpe");
        }

        $fields = explode(':', substr($cpe, strlen('cpe:2.3:')));

        if (!$part = Part::tryFrom($fields[0])) {
            throw new InvalidArgumentException("Invalid Part");
        }

        $instance = new self();
        $instance->part = $part;
        $instance->vendor = $fields[1] ?? '*';
        $instance->product = $fields[2] ?? '*';
        $instance->version = $fields[3] ?? '*';
        $instance->update = $fields[4] ?? '*';
        $instance->edition = $fields[5] ?? '*';
        $instance->language = $fields[6] ?? '*';
        $instance->swEdition = $fields[7] ?? '*';
        $instance->targetSw = $fields[8] ?? '*';
        $instance->targetHw = $fields[9] ?? '*';
        $instance->other = $fields[10] ?? '*';

        return $instance;
    }

    /**
     * Return string in format
     * cpe:<cpe_version>:<part>:<vendor>:<product>:<version>:<update>:<edition>:<language>:<sw_edition>:<target_sw>:<target_hw>:<other>
     */
    public function __toString(): string
    {
        return sprintf(
            'cpe:2.3:%s:%s:%s:%s:%s:%s:%s:%s:%s:%s:%s',
            $this->part->value,
            $this->vendor,
            $this->product,
            $this->version,
            $this->update,
            $this->edition,
            $this->language,
            $this->swEdition,
            $this->targetSw,
            $this->targetHw,
            $this->other
        );
    }
}