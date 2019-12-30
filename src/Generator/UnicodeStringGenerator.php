<?php

namespace Eris\Generator;

use Eris\Generator;
use Eris\Generator\Unicode\Codepoint;
use Eris\Random\RandomRange;

function unicode($encoding = 'utf-8')
{
    return new UnicodeStringGenerator($encoding);
}

class UnicodeStringGenerator implements Generator
{
    /**
     * @var string
     */
    private $encoding;
    
    public function __construct($encoding = 'utf-8')
    {
        $this->encoding = $encoding;
    }

    public function __invoke($size, RandomRange $rand)
    {
        $length = $rand->rand(0, $size);

        for ($built = '', $i = 0; $i < $length; $i++) {
            $n = $this->generateCodepoint($rand);
            $built .= mb_chr($n);
        }
        // can contain unprintable characters
        return GeneratedValueSingle::fromJustValue($built, 'unicode');
    }

    private function generateCodepoint(RandomRange $rand)
    {
        while (true) {
            $n = $rand->rand(Codepoint::min(), Codepoint::max());
            if (Codepoint::isNonCharacter($n)) {
                continue;
            }
            return $n;
        }
    }
    
    public function shrink(GeneratedValueSingle $element)
    {
        if ($element->unbox() === '') {
            return $element;
        }
        return GeneratedValueSingle::fromJustValue(
            mb_substr($element->unbox(), 0, -1, $this->encoding),
            'unicode'
        );
    }
}
