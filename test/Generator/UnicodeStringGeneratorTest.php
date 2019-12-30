<?php

namespace Eris\Generator;

use Eris\Generator\Unicode\Codepoint;
use Eris\Random\RandomRange;
use Eris\Random\RandSource;

class UnicodeStringGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RandomRange
     */
    private $rand;

    private $encoding = 'utf-8';

    public function setUp()
    {
        $this->rand = new RandomRange(new RandSource());
    }

    public function testRandomlyPicksLengthAndCharacters()
    {
        $size           = 10;
        $generator      = new UnicodeStringGenerator($this->encoding);
        $lengths        = [];
        $usedCodepoints = [];
        for ($i = 0; $i < 5000; $i++) {
            $value  = $generator($size, $this->rand)->unbox();
            $length = mb_strlen($value, $this->encoding);
            $this->assertLessThanOrEqual(10, $length);
            $lengths        = $this->accumulateLengths($lengths, $length);
            $usedCodepoints = $this->accumulateUsedCodepoints($usedCodepoints, $value);
        }
        $this->assertSame(11, count($lengths));
        foreach (array_keys($usedCodepoints) as $codePoint) {
            $this->assertTrue(Codepoint::isValid($codePoint));
        }
    }

    public function testRespectsTheGenerationSize()
    {
        $generationSize = 100;
        $generator      = new UnicodeStringGenerator($this->encoding);
        $value          = $generator($generationSize, $this->rand)->unbox();

        $this->assertLessThanOrEqual($generationSize, mb_strlen($value, $this->encoding));
    }

    public function testShrinksByChoppingOffChars()
    {
        $generator = new UnicodeStringGenerator($this->encoding);
        $value     = $generator->__invoke(10, $this->rand);
        $expected  = mb_substr($value->unbox(), 0, -1, $this->encoding);
        $message   = sprintf(
            "Chopping of one character from did not give expected value.\n" .
            "Input code point(s): %s\n" .
            "Expected code point(s): %s",
            implode(',', $this->strToByteSeq($value->unbox())),
            implode(',', $this->strToByteSeq($expected))
        );
        $this->assertSame(
            $expected,
            $generator->shrink($value)->unbox(),
            $message
        );
    }

    private function strToByteSeq($s)
    {
        for ($seq = [], $length = mb_strlen($s, $this->encoding), $i = 0; $i < $length; $i++) {
            $seq[] = mb_ord(mb_substr($s, $i, 1), $this->encoding);
        }
        return $seq;
    }

    public function testCannotShrinkTheEmptyString()
    {
        $generator    = new UnicodeStringGenerator();
        $minimumValue = GeneratedValueSingle::fromJustValue('');
        $this->assertEquals($minimumValue, $generator->shrink($minimumValue));
    }

    private function accumulateLengths(array $lengths, $length)
    {
        if (!isset($lengths[$length])) {
            $lengths[$length] = 0;
        }
        $lengths[$length]++;
        return $lengths;
    }

    private function accumulateUsedCodepoints(array $usedCodepoints, $value)
    {
        for ($j = 0, $length = mb_strlen($value, $this->encoding); $j < $length; $j++) {
            $char      = mb_substr($value, $j, 1, $this->encoding);
            $codepoint = mb_ord($char, $this->encoding);
            if (!isset($usedCodepoints[$codepoint])) {
                $usedCodepoints[$codepoint] = 0;
            }
            $usedCodepoints[$codepoint]++;
        }
        return $usedCodepoints;
    }
}
