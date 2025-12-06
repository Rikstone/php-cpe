<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Exception;

use Exception;

class InvalidLogicalValueException extends Exception
{
    protected $message = 'Invalid logical value. Should be ANY or NA.';
}