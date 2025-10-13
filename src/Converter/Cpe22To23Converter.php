<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Converter;

use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Cpe23;

class Cpe22To23Converter
{
    public function convert(Cpe22 $cpe22): Cpe23
    {
        return (new Cpe23())
            ->setPart($cpe22->getPart())
            ->setVendor($cpe22->getVendor())
            ->setProduct($cpe22->getProduct())
            ->setVersion($cpe22->getVersion() ?? '*')
            ->setUpdate($cpe22->getUpdate() ?? '*')
            ->setEdition($cpe22->getEdition() ?? '*')
            ->setLanguage($cpe22->getLanguage() ?? '*')
            ->setSwEdition('*')
            ->setTargetSw('*')
            ->setTargetHw('*')
            ->setOther('*');
    }
}