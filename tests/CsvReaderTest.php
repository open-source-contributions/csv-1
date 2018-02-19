<?php

namespace Odan\Test;

use Odan\Csv\CsvReader;
use Odan\Encoding\Utf8Encoding;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * CsvReaderTest
 *
 * @coversDefaultClass \Odan\Csv\CsvReader
 */
class CsvReaderTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    /**
     * @var CsvReader
     */
    protected $csvReader;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
        $this->csvReader = new CsvReader();
    }

    /**
     * Test create object.
     *
     * @return void
     * @covers ::__construct
     */
    public function testInstance()
    {
        $this->assertInstanceOf(CsvReader::class, $this->csvReader);
    }

    /**
     * Test that it can set the specific encoding.
     *
     * @return void
     * @covers ::setEncoding
     */
    public function testSetEncoding()
    {
        $this->assertNull($this->csvReader->setEncoding(new Utf8Encoding()));
    }

    /**
     * Test that it can set the delimiter.
     *
     * @return void
     * @covers ::setDelimiter
     */
    public function testSetDelimiter()
    {
        $this->assertNull($this->csvReader->setDelimiter(','));
    }

    /**
     * Test that it can set the enclosure.
     *
     * @return void
     * @covers ::setEnclosure
     */
    public function testSetEnclosure()
    {
        $this->assertNull($this->csvReader->setEnclosure('"'));
    }

    /**
     * Test that it can set the escape.
     *
     * @return void
     * @covers ::setEscape
     */
    public function testSetEscape()
    {
        $this->assertNull($this->csvReader->setEscape('\\'));
    }

    /**
     * Test that it can set the new line.
     *
     * @return void
     * @covers ::setNewline
     */
    public function testSetNewline()
    {
        $this->assertNull($this->csvReader->setNewline(PHP_EOL));
    }

    /**
     * Test that it can process csv strings.
     *
     * @return void
     * @covers ::process
     * @covers ::parseHeader
     * @covers ::getCsvHeaders
     */
    public function testProcess()
    {
        $this->csvReader->setNewline(PHP_EOL);
        $this->csvReader->setDelimiter(',');

        $this->assertTrue($this->csvReader->process('header1,header2,header3,header4'));
    }

    /**
     * Test that it can process csv strings when preventing duplicate csv headers.
     *
     * @return void
     * @covers ::process
     * @covers ::parseHeader
     * @covers ::getCsvHeaders
     */
    public function testProcessWithPreventingDuplicateCsvHeader()
    {
        $this->csvReader->setNewline(PHP_EOL);
        $this->csvReader->setDelimiter(',');

        $this->assertTrue($this->csvReader->process('header1,header2,header3,header1'));
    }

    /**
     * Test that it can return null when fetching one row csv strings.
     *
     * @return void
     * @covers ::fetch
     */
    public function testFetchWithOneRowData()
    {
        $this->csvReader->setNewline(PHP_EOL);
        $this->csvReader->setDelimiter(',');
        $this->csvReader->process('this,is,csv,content');

        $this->assertNull($this->csvReader->fetch());
    }

    /**
     * Test that it can fetch multiple row csv strings.
     *
     * @return void
     * @covers ::fetch
     */
    public function testFetchWithMultipleRowData()
    {
        $this->csvReader->setNewline(PHP_EOL);
        $this->csvReader->setDelimiter(',');
        $this->csvReader->process('header1,header2,header3,header4'.PHP_EOL.'this,is,csv,content'.PHP_EOL.'this,is,csv,content');
        $result = $this->csvReader->fetch();

        $this->assertSame('this', $result['header1']);
        $this->assertSame('is', $result['header2']);
        $this->assertSame('csv', $result['header3']);
        $this->assertSame('content', $result['header4']);

        $result = $this->csvReader->fetch();

        $this->assertSame('this', $result['header1']);
        $this->assertSame('is', $result['header2']);
        $this->assertSame('csv', $result['header3']);
        $this->assertSame('content', $result['header4']);
    }
}
