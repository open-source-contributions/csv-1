<?php

namespace Selective\Test;

use Selective\Encoding\Utf8Encoding;
use PHPUnit\Framework\TestCase;

/**
 * Utf8EncodingTest
 *
 * @coversDefaultClass \Selective\Encoding\Utf8Encoding
 */
class Utf8EncodingTest extends TestCase
{
    /**
     * Test encode the utf8 encoding data.
     *
     * @return mixed
     * @covers ::encode
     */
    public function testEncode()
    {
        $data = 'utf8_data';
        $utf8Encode = new Utf8Encoding();

        $this->assertSame($data, $utf8Encode->encode($data));
    }
}
