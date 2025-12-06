<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming;

final class EditionPacker
{
    /**
     * Packs the values of the five arguments into the single edition component.
     *
     * If all the values are blank, the function returns a blank.
     *
     * @param string $ed edition string
     * @param string $sw_ed software edition string
     * @param string $t_sw target software string
     * @param string $t_hw target hardware string
     * @param string $oth other edition information string
     * @return string the packed string, or blank
     */
    public static function pack(string $ed, string $sw_ed, string $t_sw, string $t_hw, string $oth): string
    {
        if ($sw_ed == '' && $t_sw == '' && $t_hw == '' && $oth == '') {
            return $ed;
        }

        return sprintf('~%s~%s~%s~%s~%s', $ed, $sw_ed, $t_sw, $t_hw, $oth);
    }
}