<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Exception;

class InvalidLogicalValueException extends CommonException
{
    protected $message = 'Invalid logical value. Should be ANY or NA.';
}