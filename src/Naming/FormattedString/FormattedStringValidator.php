<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\FormattedString;

use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidFormattedStringException;

final class FormattedStringValidator
{
    /**
     * Validate string with rules:
     *  String must start with the characters "cpe:2.3:"
     *  String must contain 11 components
     *  String must contain correct part component
     *
     * @throws InvalidFormattedStringException
     */
    public static function validate(string $formattedString): void
    {
        if (!str_starts_with($formattedString, 'cpe:2.3:')) {
            throw new InvalidFormattedStringException('Formatted string must start with "cpe:2.3:"');
        }

        $components = explode(':', substr($formattedString, strlen('cpe:2.3:')));

        if (count($components) != 11) {
            throw new InvalidFormattedStringException('Formatted string must contain 11 components');
        }

        foreach ($components as $index => $component) {
            if ($component === '') {
                throw new InvalidFormattedStringException(
                    sprintf('Empty component found at position %d', $index + 1)
                );
            }
        }

        if (!$components[0]) {
            throw new InvalidFormattedStringException('URI must contain an part component');
        }

        if (!Part::tryFrom($components[0])) {
            throw new InvalidFormattedStringException('Part component must be "a", "h" or "o"');
        }
    }
}