<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\FormattedString;

use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\LogicalValue;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidFormattedStringException;
use Rikstone\Cpe\WellFormedName;

final class Unbinder
{
    /**
     * Offset of component position number in formatted string
     */
    private const int COMPONENT_POSITION_OFFSET = 2;

    /**
     * Unbind a CPE 2.3 formatted string into a WellFormedName.
     *
     * @throws InvalidFormattedStringException
     */
    public function unbindFS(string $formattedString): WellFormedName
    {
        FormattedStringValidator::validate($formattedString);

        $wfn = new WellFormedName(Part::A);

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

        foreach ($attributes as $i => $attribute) {
            $componentIndex = $i + self::COMPONENT_POSITION_OFFSET;

            $rawValue = $this->getComponentFromFormattedString($formattedString, $componentIndex);
            $value = $this->unbindValue($rawValue);

            if ($attribute === 'part') {
                if ($value instanceof LogicalValue) {
                    throw new InvalidFormattedStringException("Part component cannot be a logical value");
                }

                if (!$part = Part::tryFrom($value)) {
                    throw new InvalidFormattedStringException("Invalid part value: '$value'");
                }

                $wfn->part = $part;
            } else {
                $wfn->$attribute = $value;
            }
        }

        return $wfn;
    }

    /**
     * Returns the i-th field of a formatted string. Colon ':' is the delimiter unless escaped by a backslash.
     */
    private function getComponentFromFormattedString(string $formattedString, int $componentIndex): string
    {
        $currentIndex = 0;
        $start = 0;
        $length = strlen($formattedString);

        for ($i = 0; $i <= $length; $i++) {
            if ($i === $length || ($formattedString[$i] === ':' && ($i === 0 || $formattedString[$i - 1] !== '\\'))) {
                if ($currentIndex === $componentIndex) {
                    return substr($formattedString, $start, $i - $start);
                }

                $currentIndex++;
                $start = $i + 1;
            }
        }

        return '';
    }

    /**
     * Converts a raw formatted string value into logical value or properly quoted string.
     *
     * @param string $value
     * @return LogicalValue|string
     * @throws InvalidFormattedStringException
     */
    private function unbindValue(string $value): string|LogicalValue
    {
        return match ($value) {
            '*' => new Any(),
            '-' => new NA(),
            default => $this->addQuoting($value),
        };
    }

    /**
     * Adds escaping for non-alphanumeric characters, validates '*' and '?' positions.
     *
     * @param string $value
     * @return string
     * @throws InvalidFormattedStringException
     */
    private function addQuoting(string $value): string
    {
        $result = '';
        $length = strlen($value);
        $embedded = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $value[$i];

            if ($this->isAlphanumeric($char)) {
                $result .= $char;
                $embedded = true;

                continue;
            }

            if ($char === '\\') {
                $result .= $char . ($value[$i + 1] ?? '');
                $i++;
                $embedded = true;

                continue;
            }

            if ($char === '*') {
                if ($i === 0 || $i === $length - 1) {
                    $result .= '*';
                    $embedded = true;
                } else {
                    throw new InvalidFormattedStringException(
                        'Error! cannot have unquoted * embedded in formatted string.',
                    );
                }

                continue;
            }

            if ($char === '?') {
                $prevChar = $i > 0 ? $value[$i - 1] : '';
                $nextChar = $i < $length - 1 ? $value[$i + 1] : '';
                $isLegal = $i === 0 || $i === $length - 1
                    || (!$embedded && $prevChar === '?')
                    || ($embedded && $nextChar === '?');

                if ($isLegal) {
                    $result .= '?';
                    $embedded = false;
                } else {
                    throw new InvalidFormattedStringException(
                        'Error! cannot have unquoted ? embedded in formatted string.',
                    );
                }

                continue;
            }

            $result .= '\\' . $char;
            $embedded = true;
        }

        return $result;
    }

    private function isAlphanumeric(string $char): bool
    {
        return ctype_alnum($char) || $char === '_';
    }
}