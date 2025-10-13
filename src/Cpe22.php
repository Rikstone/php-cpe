<?php

declare(strict_types=1);

namespace Rikstone\Cpe;

use InvalidArgumentException;

class Cpe22 implements Cpe
{
    private Part $part;
    private string $vendor;
    private string $product;
    private ?string $version = null;
    private ?string $update = null;
    private ?string $edition = null;
    private ?string $language = null;

    public function getPart(): Part
    {
        return $this->part;
    }

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getUpdate(): ?string
    {
        return $this->update;
    }

    public function getEdition(): ?string
    {
        return $this->edition;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setPart(Part $part): self
    {
        $this->part = $part;
        return $this;
    }

    public function setVendor(string $vendor): self
    {
        $this->vendor = $this->normalizeString($vendor);
        return $this;
    }

    public function setProduct(string $product): self
    {
        $this->product = $this->normalizeString($product);
        return $this;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function setUpdate(?string $update): self
    {
        $this->update = $update;
        return $this;
    }

    public function setEdition(?string $edition): self
    {
        $this->edition = $edition;
        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public static function fromString(string $cpe): self
    {
        if (!str_starts_with($cpe, 'cpe:/')) {
            throw new InvalidArgumentException("Invalid CPE 2.2 string: $cpe");
        }

        $fields = explode(':', substr($cpe, strlen('cpe:/')));

        if (!$part = Part::tryFrom($fields[0])) {
            throw new InvalidArgumentException("Invalid Part");
        }

        $instance = new self();
        $instance->part = $part;
        $instance->vendor = $fields[1] ?? '';
        $instance->product = $fields[2] ?? '';
        $instance->version = $fields[3] ?? null;
        $instance->update = $fields[4] ?? null;
        $instance->edition = $fields[5] ?? null;
        $instance->language = $fields[6] ?? null;

        return $instance;
    }

    private function normalizeString(string $value): string
    {
        return mb_strtolower(str_replace(' ', '_', $value));
    }

    /**
     * Return string in format
     * cpe:/{part}:{vendor}:{product}:{version}:{update}:{edition}:{language}
     *
     * According to the specification, some parts may be missing
     */
    public function __toString(): string
    {
        $fields = [
            $this->part->value,
            $this->vendor,
            $this->product,
            $this->version,
            $this->update,
            $this->edition,
            $this->language,
        ];

        $fields = array_reverse($fields);

        while (!empty($fields) && ($fields[0] === null || $fields[0] === '')) {
            array_shift($fields);
        }

        $fields = array_reverse($fields);

        return 'cpe:/' . implode(':', $fields);
    }
}
