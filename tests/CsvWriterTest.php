<?php

namespace Selective\Test;

use Selective\Csv\CsvWriter;
use Selective\Encoding\Utf8Encoding;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

/**
 * CsvWriterTest
 *
 * @coversDefaultClass \Selective\Csv\CsvWriter
 */
final class CsvWriterTest extends TestCase
{

    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @var CsvWriter
     */
    private $csvWriter;

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
     */
    public function testInstance()
    {
        $this->assertInstanceOf(CsvWriter::class, $this->csvWriter);
    }

    /**
     * Test that it can throw RuntimeException when setting the null file name.
     *
     * @throws RuntimeException
     * @expectedException RuntimeException
     *
     * @return void
     */
    public function testSetFileNameWithNullFileName()
    {
        $this->csvWriter->setFileName('');
    }

    /**
     * Test that it can put the columns.
     *
     * @return void
     */
    public function testPutColumns()
    {
        $this->csvWriter->setFileName(vfsStream::url('root/output.csv'));
        $this->csvWriter->setEncoding(new Utf8Encoding());
        $this->csvWriter->setDelimiter(',');
        $this->csvWriter->setEnclosure('"');
        $this->csvWriter->setNewline("\r\n");

        $this->assertTrue($this->csvWriter->putColumns([
            'header1',
            'header2',
            'header3',
        ]));
    }

    /**
     * Test that it can put the columns.
     *
     * @return void
     */
    public function testPutColumnsOld()
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
     * @return void
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
     * @return void
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
     * @return void
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
     * @return void
     */
    public function testEscape()
    {
        $this->assertSame('"\""\"""', $this->csvWriter->escape('\"\"'));
    }

    /**
     * Test that it can set the escape/quote value to CSV string when the value is null or empty.
     *
     * @return void
     */
    public function testEscapeWithEmptyOrNullValue()
    {
        $this->assertSame('""', $this->csvWriter->escape(''));
        $this->assertSame('""', $this->csvWriter->escape(null));
    }
}
