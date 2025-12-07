<?php

declare(strict_types=1);

namespace Rikstone\Cpe\Naming\URI;

use Exception;
use Rikstone\Cpe\Common\Logical\Any;
use Rikstone\Cpe\Common\Logical\LogicalValue;
use Rikstone\Cpe\Common\Logical\NA;
use Rikstone\Cpe\Exception\InvalidURIException;

final class PercentDecoder
{
    /**
     * @throws Exception
     */
    public static function decode(string $s): LogicalValue|string
    {
        if ($s === '') {
            return new Any();
        }

        if ($s === '-') {
            return new NA();
        }

        $s = strtolower($s);
        $result = '';
        $length = strlen($s);
        $embedded = false;

        for ($i = 0; $i < $length;) {
            $c = $s[$i];

            if ($c === '.' || $c === '-' || $c === '~') {
                $result .= '\\' . $c;
                $embedded = true;
                $i++;
                continue;
            }

            if ($c !== '%') {
                $result .= $c;
                $embedded = true;
                $i++;

                continue;
            }

            if ($i + 2 >= $length) {
                self::throwException();
            }

            $form = substr($s, $i, 3);

            if ($form === '%01') {
                if (
                    $i === 0
                    || $i === $length - 3
                    || (!$embedded && $i >= 3 && substr($s, $i - 3, 3) === '%01')
                    || ($embedded && $i + 6 <= $length && substr($s, $i + 3, 3) === '%01')
                ) {
                    $result .= '?';
                    $i += 3;
                    continue;
                }

                self::throwException();
            }

            if ($form === '%02') {
                if ($i === 0 || $i === $length - 3) {
                    $result .= '*';
                    $i += 3;

                    continue;
                }

                self::throwException();
            }

            $decodeMap = PercentMap::getDecodeMap();

            if (isset($decodeMap[$form])) {
                $result .= '\\' . $decodeMap[$form];
                $embedded = true;
                $i += 3;

                continue;
            }

            self::throwException();
        }

        return $result;
    }

    /**
     * @throws InvalidURIException
     */
    private static function throwException(): void
    {
        throw new InvalidURIException('Error decoding string');
    }
}
