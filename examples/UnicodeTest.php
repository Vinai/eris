<?php
use Eris\Generator;
use Eris\TestTrait;

class UnicodeTest extends PHPUnit_Framework_TestCase
{
    use TestTrait;

    public function testUnicodeGeneraton()
    {
        $this->forAll(Generator\unicode())
            ->then(
                function ($string) {
                    $this->assertInternalType('string', $string);
                    $this->assertEquals(
                        $string,
                        $string . '',
                        "Concatenating '$string' to ''"
                    );
                }
            );
    }
}