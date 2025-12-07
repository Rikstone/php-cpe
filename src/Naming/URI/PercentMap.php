<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

/**
 * @internal
 */
final class PercentMap
{
    /**
     * Map: char → percent
     *
     * @var array<string,string>
     */
    private const array MAP = [
        "!"  => "%21",
        "\"" => "%22",
        "#"  => "%23",
        "$"  => "%24",
        "%"  => "%25",
        "&"  => "%26",
        "'"  => "%27",
        "("  => "%28",
        ")"  => "%29",
        "*"  => "%2a",
        "+"  => "%2b",
        ","  => "%2c",
        "/"  => "%2f",
        ":"  => "%3a",
        ";"  => "%3b",
        "<"  => "%3c",
        "="  => "%3d",
        ">"  => "%3e",
        "?"  => "%3f",
        "@"  => "%40",
        "["  => "%5b",
        "\\" => "%5c",
        "]"  => "%5d",
        "^"  => "%5e",
        "`"  => "%60",
        "{"  => "%7b",
        "|"  => "%7c",
        "}"  => "%7d",
        "~"  => "%7e",
    ];

    /**
     * Get map for encoding process
     *
     * @return array<string, string>
     */
    public static function getEncodeMap(): array
    {
        return self::MAP;
    }

    /**
     * Get symbols map for decoding process
     *
     * @return array<string, string>
     */
    public static function getDecodeMap(): array
    {
        $decode = [];

        foreach (self::MAP as $char => $code) {
            $decode[strtolower($code)] = $char;
        }

        return $decode;
    }
}