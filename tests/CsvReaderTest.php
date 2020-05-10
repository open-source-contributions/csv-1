<?php

namespace Selective\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Selective\Csv\CsvReader;
use Selective\Encoding\Utf8Encoding;

/**
 * CsvReaderTest.
 *
 * @coversDefaultClass \Selective\Csv\CsvReader
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
     * Setup.
     */
    protected function setUp(): void
    {
        $this->root = vfsStream::setup('root');
        $this->csvReader = new CsvReader();
    }

    /**
     * Test create object.
     *
     * @return void
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(CsvReader::class, $this->csvReader);
    }

    /**
     * Test that it can process csv strings.
     *
     * @return void
     */
    public function testProcess(): void
    {
        $this->csvReader->setEncoding(new Utf8Encoding());
        $this->csvReader->setNewline("\n");
        $this->csvReader->setDelimiter(',');
        $this->csvReader->setEscape('\\');
        $this->csvReader->setEnclosure('"');

        $this->csvReader->process('header1,header2,header3,header4');
        $this->assertTrue(true);
    }

    /**
     * Test that it can process csv strings when preventing duplicate csv headers.
     *
     * @return void
     */
    public function testProcessWithPreventingDuplicateCsvHeader(): void
    {
        $this->csvReader->setNewline("\n");
        $this->csvReader->setDelimiter(',');

        $this->csvReader->process('header1,header2,header3,header1');
        $this->assertTrue(true);
    }

    /**
     * Test that it can return null when fetching one row csv strings.
     *
     * @return void
     */
    public function testFetchWithOneRowData(): void
    {
        $this->csvReader->setNewline("\n");
        $this->csvReader->setDelimiter(',');
        $this->csvReader->process('this,is,csv,content');

        $this->assertNull($this->csvReader->fetch());
    }

    /**
     * Test that it can fetch multiple row csv strings.
     *
     * @return void
     */
    public function testFetchWithMultipleRowData(): void
    {
        $this->csvReader->setNewline("\n");
        $this->csvReader->setDelimiter(',');
        $this->csvReader->process(
            'header1,header2,header3,header4' . "\n" . 'this,is,csv,content' . "\n" . 'this,is,csv,content'
        );
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
