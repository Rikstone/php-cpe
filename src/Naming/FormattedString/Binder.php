<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\FormattedString;

use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\LogicalValue;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidLogicalValueException;
use Rikstone\Cpe\WellFormedName;

final class Binder
{
    /**
     * Convert a WellFormedName into a CPE 2.3 formatted string.
     *
     * @throws InvalidLogicalValueException
     */
    public function bindToFS(WellFormedName $wfn): string
    {
        $attributes = [
            'part',
            'vendor',
            'product',
            'version',
            'update',
            'edition',
            'language',
            'swEdition',
            'targetSW',
            'targetHW',
            'other',
        ];

        $fs = 'cpe:2.3:';

        foreach ($attributes as $i => $attribute) {
            $value = $wfn->$attribute;

            if ($value instanceof Part) {
                $value = $value->value;
            }

            $fs .= $this->bindValueForFS($value);

            if ($i !== array_key_last($attributes)) {
                $fs .= ':';
            }
        }

        return $fs;
    }

    /**
     * Convert a WFN attribute to its string representation in formatted string.
     *
     * @param string|LogicalValue $value
     * @return string
     *
     * @throws InvalidLogicalValueException
     */
    private function bindValueForFS(string|LogicalValue $value): string
    {
        if ($value instanceof Any) {
            return '*';
        }

        if ($value instanceof NA) {
            return '-';
        }

        if (!is_string($value)) {
            throw new InvalidLogicalValueException(
                'Unexpected logical value in WFN attribute'
            );
        }

        return $this->processQuotedChars($value);
    }

    /**
     * Process escaped characters for formatted string.
     *
     * Rules:
     * - Backslash followed by '.', '-', '_' → unescaped
     * - All other backslash-escaped chars → retained with backslash
     * - Unescaped chars → pass through
     */
    private function processQuotedChars(string $s): string
    {
        $result = '';
        $length = strlen($s);

        for ($i = 0; $i < $length; $i++) {
            $char = $s[$i];

            if ($char !== '\\') {
                $result .= $char;
                continue;
            }

            $i++;
            if ($i >= $length) {
                $result .= '\\';
                break;
            }

            $nextChar = $s[$i];

            if (in_array($nextChar, ['.', '-', '_'], true)) {
                $result .= $nextChar;
            } else {
                $result .= '\\' . $nextChar;
            }
        }

        return $result;
    }
}