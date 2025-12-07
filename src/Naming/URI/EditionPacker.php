<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

final class EditionPacker
{
    /**
     * Packs the values of the five arguments into the single edition component.
     *
     * If all the values are blank, the function returns a blank.
     *
     * @param string $edition edition
     * @param string $swEdition software edition
     * @param string $targetSW target software
     * @param string $targetHW target hardware
     * @param string $other other edition information
     * @return string the packed string, or blank
     */
    public static function pack(
        string $edition,
        string $swEdition,
        string $targetSW,
        string $targetHW,
        string $other,
    ): string {
        if ($swEdition == '' && $targetSW == '' && $targetHW == '' && $other == '') {
            return $edition;
        }

        return sprintf('~%s~%s~%s~%s~%s', $edition, $swEdition, $targetSW, $targetHW, $other);
    }
}