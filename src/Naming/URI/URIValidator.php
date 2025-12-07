<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

use Rikstone\Cpe\Common\Part;
use Rikstone\Cpe\Exception\InvalidURIException;

final class URIValidator
{
    /**
     * Validate uri with rules:
     *  URI must start with the characters "cpe:/"
     *  A URI may not contain more than 7 components
     *  A URI must contain correct part component
     *
     * @throws InvalidURIException
     */
    public static function validate(string $uri): void
    {
        if (!str_starts_with($uri, 'cpe:/')) {
            throw new InvalidURIException('URI must start with "cpe:/"');
        }

        $components = explode(':', substr($uri, strlen('cpe:/')));

        if (count($components) > 7) {
            throw new InvalidURIException('URI must contain 7 components or less');
        }

        if (!$components[0]) {
            throw new InvalidURIException('URI must contain an part component');
        }

        if (!Part::tryFrom($components[0])) {
            throw new InvalidURIException('Part component must be "a", "h" or "o"');
        }
    }
}