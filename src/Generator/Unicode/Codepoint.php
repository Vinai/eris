<?php

namespace Eris\Generator\Unicode;

final class Codepoint
{
    /**
     * Lowest unicode code point
     *
     * @return int
     */
    public static function min()
    {
        return 0;
    }

    /**
     * Highest unicode code point
     *
     * @return int
     */
    public static function max()
    {
        return hexdec('10FFFF');
    }

    /**
     * Returns true if $n could be a valid code point that might be assigned to a unicode character.
     *
     * Please note that $n may still not represent an actually assigned code point.
     * All this method does is confirm it's within the valid bounds of potentially
     * assigned unicode code points.
     *
     * @param int $n
     * @return bool
     */
    public static function isValid($n)
    {
        return $n >= self::min() &&
            $n <= self::max() &&
            !self::isNonCharacter($n);
    }

    /**
     * Return true if $n falls in the range of not assigned unicode code points..
     *
     * Note that the process-internal code points may still be used
     * internally by an application, but they will never be assigned.
     *
     * @param int $n Unicode code point
     * @return bool
     */
    public static function isNonCharacter($n)
    {
        switch (true) {
            // used for UTF-16 surrogate pair encoding, can't be used as individual code points
            case ($n >= hexdec('00D800') && $n <= hexdec('00DFFF')):
            case ($n >= hexdec('00FDD0') && $n <= hexdec('00FDEF')):
                // used for process-internal
            case ($n === hexdec('00FFFE') || $n === hexdec('00FFFF')):
            case ($n === hexdec('01FFFE') || $n === hexdec('01FFFF')):
            case ($n === hexdec('02FFFE') || $n === hexdec('02FFFF')):
            case ($n === hexdec('03FFFE') || $n === hexdec('03FFFF')):
            case ($n === hexdec('04FFFE') || $n === hexdec('04FFFF')):
            case ($n === hexdec('05FFFE') || $n === hexdec('05FFFF')):
            case ($n === hexdec('06FFFE') || $n === hexdec('06FFFF')):
            case ($n === hexdec('07FFFE') || $n === hexdec('07FFFF')):
            case ($n === hexdec('08FFFE') || $n === hexdec('08FFFF')):
            case ($n === hexdec('09FFFE') || $n === hexdec('09FFFF')):
            case ($n === hexdec('0AFFFE') || $n === hexdec('0AFFFF')):
            case ($n === hexdec('0BFFFE') || $n === hexdec('0BFFFF')):
            case ($n === hexdec('0CFFFE') || $n === hexdec('0CFFFF')):
            case ($n === hexdec('0DFFFE') || $n === hexdec('0DFFFF')):
            case ($n === hexdec('0EFFFE') || $n === hexdec('0EFFFF')):
            case ($n === hexdec('0FFFFE') || $n === hexdec('0FFFFF')):
            case ($n === hexdec('10FFFE') || $n === hexdec('10FFFF')):
                return true;
            default:
                return false;
        }
    }
}