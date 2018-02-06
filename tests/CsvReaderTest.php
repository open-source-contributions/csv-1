<?php

namespace Odan\Test;

use Odan\Csv\CsvReader;
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
        $this->assertInstanceOf(CsvReader::class, new CsvReader());
    }
}
