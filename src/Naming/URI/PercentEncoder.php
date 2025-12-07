<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

final class PercentEncoder
{
    /**
     * Returns the appropriate percent-encoding of character.
     * Certain characters are returned without encoding.
     *
     * @param string $value the single character string to be encoded
     *
     * @return string the percent encoded string
     */
    public static function encode(string $value): string
    {
        return PercentMap::getEncodeMap()[$value] ?? $value;
    }
}