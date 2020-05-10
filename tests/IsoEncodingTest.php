<?php

namespace Selective\Test;

use PHPUnit\Framework\TestCase;
use Selective\Encoding\IsoEncoding;

/**
 * IsoEncodingTest.
 *
 * @coversDefaultClass \Selective\Encoding\IsoEncoding
 */
class IsoEncodingTest extends TestCase
{
    /**
     * Test encode the null data.
     *
     * @return void
     */
    public function testEncodeWithNullData(): void
    {
        $isoEncoding = new IsoEncoding();

        $this->assertNull($isoEncoding->encode(null));
    }

    /**
     * Test encode the empty data.
     *
     * @return void
     */
    public function testEncodeWithEmptyData(): void
    {
        $data = '';
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }

    /**
     * Test encode the utf8 encoding data.
     *
     * @return void
     */
    public function testEncodeWithUtf8Data(): void
    {
        $data = 'This is the utf-8 encoding data';
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }

    /**
     * Test encode the ISO-85991 encoding data.
     *
     * @return void
     */
    public function testEncodeWithIso85991Data(): void
    {
        $data = file_get_contents(__DIR__ . '/data_iso85991.txt');
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }
}
