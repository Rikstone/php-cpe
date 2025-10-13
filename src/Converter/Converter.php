<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Converter;

use Rikstone\Cpe\Cpe;
use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Cpe23;

/**
 * Converter of CPE objects
 */
class Converter
{
    /**
     * @param Cpe $cpe - Source CPE object for converting
     * @param string $to - Required CPE version
     */
    public function convert(Cpe $cpe, string $to): Cpe
    {
        return match (sprintf("%s->%s", $cpe::class, $to)) {
            sprintf("%s->%s", Cpe22::class, Cpe23::class) => (new Cpe22To23Converter())->convert($cpe),
            sprintf("%s->%s", Cpe23::class, Cpe22::class) => (new Cpe23To22Converter())->convert($cpe),
            default => throw new \InvalidArgumentException(
                sprintf("Unsupported conversion: %s -> %s", $cpe::class, $to)
            )
        };
    }
}