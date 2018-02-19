<?php

namespace Odan\Test;

use Odan\Csv\CsvWriter;
use Odan\Encoding\Utf8Encoding;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * CsvWriterTest
 *
 * @coversDefaultClass \Odan\Csv\CsvWriter
 */
class CsvWriterTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    /**
     * @var CsvWriter
     */
    protected $csvWriter;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
        $file = vfsStream::url('root/output.csv');
        $this->csvWriter = new CsvWriter($file);
    }

    /**
     * Test create object.
     *
     * @return void
     * @covers ::__construct
     */
    public function testInstance()
    {
        $this->assertInstanceOf(CsvWriter::class, $this->csvWriter);
    }

    /**
     * Test that it can throw RuntimeException when setting the null file name.
     *
     * @throws RuntimeException
     * @covers ::setFileName
     * @expectedException RuntimeException
     */
    public function testSetFileNameWithNullFileName()
    {
        $this->csvWriter->setFileName('');
    }

    /**
     * Test that it can put the columns.
     *
     * @return bool
     * @covers ::setFileName
     * @covers ::setEncoding
     * @covers ::setEnclosure
     * @covers ::setNewline
     * @covers ::setDelimiter
     * @covers ::putColumns
     */
    public function testPutColumns()
    {
        $this->csvWriter->setFileName(vfsStream::url('root/output.csv'));
        $this->csvWriter->setEncoding(new Utf8Encoding());
        $this->csvWriter->setDelimiter(',');
        $this->csvWriter->setEnclosure('"');
        $this->csvWriter->setNewline("\r\n");

        $this->assertTrue($this->csvWriter->putColumns([
            ['text' => 'header1'],
            ['text' => 'header2'],
            ['text' => 'header3'],
        ]));
    }

    /**
     * Test that it can put the single row.
     *
     * @return bool
     * @covers ::putRow
     * @covers ::rowToCsv
     */
    public function testPutRow()
    {
        $this->assertTrue($this->csvWriter->putRow([
            'this,is,csv,row'
        ]));
    }

    /**
     * Test that it can put the multiple rows.
     *
     * @return bool
     * @covers ::putRows
     * @covers ::rowToCsv
     */
    public function testPutRows()
    {
        $this->assertTrue($this->csvWriter->putRows([
            ['this,is,csv,first,row'],
            ['this,is,csv,second,row'],
        ]));
    }

    /**
     * Test that it can put the multiple rows and set the columns.
     *
     * @return bool
     * @covers ::putRows
     * @covers ::rowToCsv
     */
    public function testPutRowsWithSpecificColumns()
    {
        $this->csvWriter->putColumns([
            ['text' => 'header1'],
            ['text' => 'header2'],
            ['text' => 'header3'],
            ['text' => 'header4'],
            ['text' => 'header5'],
        ]);
        $this->assertTrue($this->csvWriter->putRows([
            ['this,is,csv,first,row'],
            ['this,is,csv,second,row'],
        ]));
    }

    /**
     * Test that it can set the escape/quote value to CSV string.
     *
     * @return bool
     * @covers ::escape
     */
    public function testEscape()
    {
        $this->assertSame('"\""\"""', $this->csvWriter->escape('\"\"'));
    }

    /**
     * Test that it can set the escape/quote value to CSV string when the value is null or empty.
     *
     * @return bool
     * @covers ::escape
     */
    public function testEscapeWithEmptyOrNullValue()
    {
        $this->assertSame('""', $this->csvWriter->escape(''));
        $this->assertSame('""', $this->csvWriter->escape(null));
    }
}
