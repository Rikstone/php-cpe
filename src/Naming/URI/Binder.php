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
                $value = $wfn->part->value;
            }

            if ($attribute == 'edition') {
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

            $uri .= sprintf('%s:', $value);
        }

        return $this->trim($uri);
    }

    /**
     * @throws InvalidLogicalValueException
     */
    private function bindValueForURI(string|LogicalValue $value): string
    {
        if (!is_string($value)) {
            if ($value instanceof Any) {
                return '';
            }

            if ($value instanceof NA) {
                return '-';
            }

            throw new InvalidLogicalValueException();
        }

        return $this->transformForUri($value);
    }

    private function transformForURI(string $value): string
    {
        $result = '';
        $idx = 0;

        while ($idx < strlen($value)) {
            $thisChar = substr($value, $idx, 1);

            if ($this->isAlphanumeric($thisChar)) {
                $result .= $thisChar;
                $idx = $idx + 1;
                continue;
            }

            if ($thisChar == "\\") {
                $idx = $idx + 1;
                $nextChar = substr($value, $idx, 1);
                $result .= PercentEncoder::encode($nextChar);
                $idx = $idx + 1;
                continue;
            }

            if ($thisChar == '?') {
                $result .= '%01';
            }

            if ($thisChar == '*') {
                $result .= '%02';
            }

            $idx = $idx + 1;
        }

        return $result;
    }

    private function isAlphanumeric(string $value): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9_]+$/', $value);
    }

    private function trim(string $value): string
    {
        $reversedString = strrev($value);
        $idx = 0;

        for ($i = 0; $i != strlen($reversedString); $i++) {
            if (substr($reversedString, $i, 1) == ":") {
                $idx = $idx + 1;
            } else {
                break;
            }
        }

        return strrev(substr($reversedString, $idx, strlen($reversedString) - $idx));
    }
}