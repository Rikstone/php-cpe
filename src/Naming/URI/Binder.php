<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\LogicalValue;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidLogicalValueException;
use Rikstone\Cpe\WellFormedName;

final class Binder
{
    /**
     * @throws InvalidLogicalValueException
     */
    public function bindToURI(WellFormedName $wfn): string
    {
        $uri = 'cpe:/';

        $attributes = [
            'part',
            'vendor',
            'product',
            'version',
            'update',
            'edition',
            'language',
        ];

        foreach ($attributes as $attribute) {
            $value = $wfn->$attribute;

            if ($value instanceof Part) {
                $value = $value->value;
            }

            if ($attribute === 'edition') {
                $value = EditionPacker::pack(
                    $this->bindValueForURI($wfn->edition),
                    $this->bindValueForURI($wfn->swEdition),
                    $this->bindValueForURI($wfn->targetSW),
                    $this->bindValueForURI($wfn->targetHW),
                    $this->bindValueForURI($wfn->other),
                );
            } else {
                $value = $this->bindValueForURI($value);
            }

            $uri .= $value . ':';
        }

        return $this->trimSuffixColons($uri);
    }

    /**
     * Converts a single logical or string WFN value into URI syntax.
     *
     * @throws InvalidLogicalValueException
     */
    private function bindValueForURI(string|LogicalValue $value): string
    {
        if ($value instanceof Any) {
            return '';
        }

        if ($value instanceof NA) {
            return '-';
        }

        if (!is_string($value)) {
            throw new InvalidLogicalValueException('Unexpected logical value in WFN attribute');
        }

        return $this->transformForURI($value);
    }

    /**
     * Transform WFN-safe string into CPE 2.2 URI-safe string.
     */
    private function transformForURI(string $value): string
    {
        $result = '';
        $length = strlen($value);

        for ($i = 0; $i < $length; ) {
            $char = $value[$i];

            if ($this->isAlphanumeric($char)) {
                $result .= $char;
                $i++;
                continue;
            }

            if ($char === '\\') {
                $i++;
                if ($i >= $length) {
                    break;
                }

                $nextChar = $value[$i];
                $result .= PercentEncoder::encode($nextChar);
                $i++;
                continue;
            }

            if ($char === '?') {
                $result .= '%01';
                $i++;
                continue;
            }

            if ($char === '*') {
                $result .= '%02';
                $i++;
                continue;
            }

            $result .= PercentEncoder::encode($char);
            $i++;
        }

        return $result;
    }

    private function isAlphanumeric(string $char): bool
    {
        return ctype_alnum($char) || $char === '_';
    }

    private function trimSuffixColons(string $uri): string
    {
        return rtrim($uri, ':');
    }
}