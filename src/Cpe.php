<?php

declare(strict_types=1);

namespace Rikstone\Cpe;

interface Cpe
{
    /**
     * @param string $cpe - CPE string
     * @return Cpe
     */
    public static function fromString(string $cpe): Cpe;
}