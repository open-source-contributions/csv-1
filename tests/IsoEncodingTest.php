<?php

namespace Odan\Test;

use Odan\Csv\CsvReader;
use PHPUnit\Framework\TestCase;
use Odan\Encoding\IsoEncoding;

/**
 * IsoEncodingTest
 *
 * @coversDefaultClass \Odan\Encoding\IsoEncoding
 */
class IsoEncodingTest extends TestCase
{
    /**
     * Test encode the null data.
     *
     * @return mixed
     * @covers ::encode
     */
    public function testEncodeWithNullData()
    {
        $isoEncoding = new IsoEncoding();

        $this->assertNull($isoEncoding->encode(null));
    }

    /**
     * Test encode the empty data.
     *
     * @return mixed
     * @covers ::encode
     */
    public function testEncodeWithEmptyData()
    {
        $data = '';
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }

    /**
     * Test encode the utf8 encoding data.
     *
     * @return mixed
     * @covers ::encode
     */
    public function testEncodeWithUtf8Data()
    {
        $data = 'This is the utf-8 encoding data';
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }

    /**
     * Test encode the ISO-85991 encoding data.
     *
     * @return mixed
     * @covers ::encode
     */
    public function testEncodeWithIso85991Data()
    {
        $data = file_get_contents(__DIR__.'/data_iso85991.txt');
        $isoEncoding = new IsoEncoding();

        $this->assertSame($data, $isoEncoding->encode($data));
    }
}
