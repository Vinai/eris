<?php
use Eris\BaseTestCase;

function concatenation($first, $second)
{
    if (strlen($second) > 5) {
        $second .= 'ERROR';
    }
    return $first . $second;
}

class StringTest extends BaseTestCase
{
    public function testRightIdentityElement()
    {
        $this->forAll([
            $this->genString(),
        ])
            ->__invoke(function($string) {
                $this->assertEquals(
                    $string,
                    concatenation($string, ''),
                    "Concatenating $string to ''"
                );
            });
    }

    public function testLengthPreservation()
    {
        $this->forAll([
            $this->genString(),
            $this->genString(),
        ])
            ->__invoke(function($first, $second) {
                $result = concatenation($first, $second);
                $this->assertEquals(
                    strlen($first) + strlen($second),
                    strlen($result),
                    "Concatenating $first to $second gives $result"
                );
            });
    }
}
