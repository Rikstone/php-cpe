<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

use Exception;
use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidURIException;
use Rikstone\Cpe\WellFormedName;

final class Unbinder
{
    /**
     * Unbind CPE 2.2 URI into a WellFormedName.
     *
     * @throws InvalidURIException
     * @throws Exception
     */
    public function unbindURI(string $uri): WellFormedName
    {
        URIValidator::validate($uri);

        $components = $this->extractComponents($uri);
        $wfn = new WellFormedName(Part::A);

        $partValue = PercentDecoder::decode($components[1] ?? '');

        if (!is_string($partValue)) {
            throw new InvalidURIException("Part component cannot be a logical value");
        }

        if (!$partEnum = Part::tryFrom($partValue)) {
            throw new InvalidURIException("Invalid part value: '$partValue'");
        }

        $wfn->part = $partEnum;

        $wfn->vendor = PercentDecoder::decode($components[2] ?? '');
        $wfn->product = PercentDecoder::decode($components[3] ?? '');
        $wfn->version = PercentDecoder::decode($components[4] ?? '');
        $wfn->update = PercentDecoder::decode($components[5] ?? '');

        $editionComponent = $components[6] ?? '';

        if ($editionComponent !== '' && str_starts_with($editionComponent, '~')) {
            EditionUnpacker::unpack($editionComponent, $wfn);
        } elseif ($editionComponent === '-') {
            $wfn->edition = PercentDecoder::decode($editionComponent);
        } else {
            $wfn->edition = PercentDecoder::decode($editionComponent);
        }

        $wfn->language = PercentDecoder::decode($components[7] ?? '');

        return $wfn;
    }

    /**
     * Extract all components from a CPE 2.2 URI.
     *
     * Example:
     *   cpe:/a:microsoft:office:14
     *   → ["cpe", "a", "microsoft", "office", "14"]
     *
     * @return array<int,string>
     */
    private function extractComponents(string $uri): array
    {
        $slashPos = strpos($uri, '/');

        if ($slashPos === false) {
            return [$uri];
        }

        $prefix = substr($uri, 0, $slashPos);

        $rest = substr($uri, $slashPos + 1);

        if ($rest === '') {
            return [$prefix];
        }

        $parts = explode(':', $rest);

        array_unshift($parts, $prefix);

        return $parts;
    }
}
