<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming;

final class PercentEncoder
{
    /**
     * Returns the appropriate percent-encoding of character c.
     * Certain characters are returned without encoding.
     *
     * @param string $value the single character string to be encoded
     *
     * @return string the percent encoded string
     */
    public static function encode(string $value): string
    {
        return match ($value) {
            '!' => "%21",
            "\"" => "%22",
            "#" => "%23",
            "$" => "%24",
            "%" => "%25",
            "&" => "%26",
            "'" => "%27",
            "(" => "%28",
            ")" => "%29",
            "*" => "%2a",
            "+" => "%2b",
            "," => "%2c",
            "/" => "%2f",
            ":" => "%3a",
            ";" => "%3b",
            "<" => "%3c",
            "=" => "%3d",
            ">" => "%3e",
            "?" => "%3f",
            "@" => "%40",
            "[" => "%5b",
            "\\" => "%5c",
            "]" => "%5d",
            "^" => "%5e",
            "`" => "%60",
            "{" => "%7b",
            "|" => "%7c",
            "}" => "%7d",
            "~" => "%7e",
            default => $value,
        };
    }
}