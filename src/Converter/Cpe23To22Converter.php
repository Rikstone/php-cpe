<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Converter;

use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Cpe23;

class Cpe23To22Converter
{
    public function convert(Cpe23 $cpe23): Cpe22
    {
        return (new Cpe22())
            ->setPart($cpe23->getPart())
            ->setVendor($cpe23->getVendor())
            ->setProduct($cpe23->getProduct())
            ->setVersion($cpe23->getVersion() === '*' ? null : $cpe23->getVersion())
            ->setUpdate($cpe23->getUpdate() === '*' ? null : $cpe23->getUpdate())
            ->setEdition($cpe23->getEdition() === '*' ? null : $cpe23->getEdition())
            ->setLanguage($cpe23->getLanguage() === '*' ? null : $cpe23->getLanguage());
    }
}