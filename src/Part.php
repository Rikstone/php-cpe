<?php

declare(strict_types=1);

namespace Rikstone\Cpe;

/**
 * The first component in a CPE Name is a single letter code that designates the particular platform part that is being identified.
 * The following codes are defined for CPE 2.0.
 */
enum Part: string
{
    /**
     * Applications
     */
    case A = 'a';

    /**
     * Hardware
     */
    case H = 'h';

    /**
     * Operating Systems
     */
    case O = 'o';
}
