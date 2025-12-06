<?php

namespace Rikstone\Cpe\Common\Logical;

final class Any extends LogicalValue
{
    public function __toString(): string
    {
        return 'ANY';
    }
}
