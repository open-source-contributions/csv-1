<?php

namespace Odan\Test;

use Odan\Csv\CsvWriter;
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
     * Setup
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup('root');
    }

    /**
     * Test create object.
     *
     * @return void
     * @covers ::__construct
     */
    public function testInstance()
    {
        $file = vfsStream::url('root/output.csv');
        $this->assertInstanceOf(CsvWriter::class, new CsvWriter($file));
    }
}
