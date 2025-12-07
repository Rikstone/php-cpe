<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

use Exception;
use Rikstone\Cpe\WellFormedName;

final class EditionUnpacker
{
    /**
     * Unpacks the packed edition string (legacy edition + 4 extended attributes)
     * and sets the fields inside the provided WellFormedName.
     *
     * Format (CPE 2.3):
     *     "~edition~sw_edition~target_sw~target_hw~other"
     *
     * Empty components map to logical ANY.
     * Percent-encoded components must be decoded.
     *
     * @param string $packedEdition String starting with "~"
     * @param WellFormedName $wfn
     *
     * @return WellFormedName
     * @throws Exception
     */
    public static function unpack(string $packedEdition, WellFormedName $wfn): WellFormedName
    {
        $trimmed = substr($packedEdition, 1);

        $components = array_pad(explode('~', $trimmed), 5, '');

        [$edition, $swEdition, $targetSW, $targetHW, $other] = $components;

        $wfn->edition = PercentDecoder::decode($edition);
        $wfn->swEdition = PercentDecoder::decode($swEdition);
        $wfn->targetSW = PercentDecoder::decode($targetSW);
        $wfn->targetHW = PercentDecoder::decode($targetHW);
        $wfn->other = PercentDecoder::decode($other);

        return $wfn;
    }
}