<?php

namespace Selective\Test;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Selective\Csv\CsvWriter;
use Selective\Encoding\Utf8Encoding;

/**
 * CsvWriterTest.
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
     * Setup.
     */
    protected function setUp(): void
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
    public function testInstance(): void
    {
        $this->assertInstanceOf(CsvWriter::class, $this->csvWriter);
    }

    /**
     * Test that it can throw RuntimeException when setting the null file name.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function testSetFileNameWithNullFileName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->csvWriter->setFileName('');
    }

    /**
     * Test that it can put the columns.
     *
     * @return void
     */
    public function testPutColumns(): void
    {
        $this->csvWriter->setFileName(vfsStream::url('root/output.csv'));
        $this->csvWriter->setEncoding(new Utf8Encoding());
        $this->csvWriter->setDelimiter(',');
        $this->csvWriter->setEnclosure('"');
        $this->csvWriter->setNewline("\r\n");

        $this->csvWriter->putColumns([
            'header1',
            'header2',
            'header3',
        ]);
        $this->assertTrue(true);
    }

    /**
     * Test that it can put the columns.
     *
     * @return void
     */
    public function testPutColumnsOld(): void
    {
        $this->csvWriter->setFileName(vfsStream::url('root/output.csv'));
        $this->csvWriter->setEncoding(new Utf8Encoding());
        $this->csvWriter->setDelimiter(',');
        $this->csvWriter->setEnclosure('"');
        $this->csvWriter->setNewline("\r\n");

        $this->csvWriter->putColumns([
            ['text' => 'header1'],
            ['text' => 'header2'],
            ['text' => 'header3'],
        ]);
        $this->assertTrue(true);
    }

    /**
     * Test that it can put the single row.
     *
     * @return void
     */
    public function testPutRow(): void
    {
        $this->csvWriter->putRow([
            'this,is,csv,row',
        ]);
        $this->assertTrue(true);
    }

    /**
     * Test that it can put the multiple rows.
     *
     * @return void
     */
    public function testPutRows(): void
    {
        $this->csvWriter->putRows([
            ['this,is,csv,first,row'],
            ['this,is,csv,second,row'],
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test that it can put the multiple rows and set the columns.
     *
     * @return void
     */
    public function testPutRowsWithSpecificColumns(): void
    {
        $this->csvWriter->putColumns([
            ['text' => 'header1'],
            ['text' => 'header2'],
            ['text' => 'header3'],
            ['text' => 'header4'],
            ['text' => 'header5'],
        ]);
        $this->csvWriter->putRows([
            ['this,is,csv,first,row'],
            ['this,is,csv,second,row'],
        ]);

        $this->assertTrue(true);
    }

    /**
     * Test that it can set the escape/quote value to CSV string.
     *
     * @return void
     */
    public function testEscape(): void
    {
        $this->assertSame('"\""\"""', $this->csvWriter->escape('\"\"'));
    }

    /**
     * Test that it can set the escape/quote value to CSV string when the value is null or empty.
     *
     * @return void
     */
    public function testEscapeWithEmptyOrNullValue(): void
    {
        $this->assertSame('""', $this->csvWriter->escape(''));
        $this->assertSame('""', $this->csvWriter->escape(null));
    }
}
