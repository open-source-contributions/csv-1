<?php

namespace Selective\Csv;

use Selective\Encoding\EncodingInterface;
use Selective\Encoding\Utf8Encoding;

/**
 * CSV Reader.
 */
class CsvReader
{
    /**
     * Encoding.
     *
     * @var EncodingInterface
     */
    protected $encoding;

    /**
     * Delimiter.
     *
     * @var string
     */
    protected $delimiter = ';';

    /**
     * Enclosure.
     *
     * @var string
     */
    protected $enclosure = '"';

    /**
     * Escape.
     *
     * @var string
     */
    protected $escape = '\\';

    /**
     * New line.
     *
     * @var string
     */
    protected $newline = "\n";

    /**
     * Headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Header count.
     *
     * @var int
     */
    protected $headerCount = 0;

    /**
     * Lines.
     *
     * @var array
     */
    protected $lines = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->setEncoding(new Utf8Encoding());
    }

    /**
     * Set encoding.
     *
     * @param EncodingInterface $encoding encoding (utf8)
     *
     * @return void
     */
    public function setEncoding(EncodingInterface $encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Set delimiter.
     *
     * @param string $delimiter delimiter (;)
     *
     * @return void
     */
    public function setDelimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Set enclosure.
     *
     * @param string $enclosure enclosure (")
     *
     * @return void
     */
    public function setEnclosure(string $enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * Set escape.
     *
     * @param string $escape escape (\\)
     *
     * @return void
     */
    public function setEscape(string $escape)
    {
        $this->escape = $escape;
    }

    /**
     * Set newline.
     *
     * @param string $newline escape (\n)
     *
     * @return void
     */
    public function setNewline(string $newline)
    {
        $this->newline = $newline;
    }

    /**
     * Process CSV content.
     *
     * @param string $csv CSV content
     *
     * @return bool Success
     */
    public function process(string $csv): bool
    {
        $this->lines = str_getcsv($csv, $this->newline);
        reset($this->lines);
        $this->headers = [];
        $this->headerCount = 0;
        $this->parseHeader();

        return true;
    }

    /**
     * Parse header.
     *
     * @return void
     */
    private function parseHeader()
    {
        $this->headers = [];
        $this->headerCount = 0;

        $row = $this->fetch();
        if ($row !== null) {
            $this->headers = $this->getCsvHeaders($row);
            $this->headerCount = count($this->headers);
        }
    }

    /**
     * Fetch next row.
     *
     * @return array|null The next row or null
     */
    public function fetch()
    {
        $line = current($this->lines);

        if ($line === false) {
            return null;
        }

        next($this->lines);

        // Encode data
        $line = $this->encoding->encode($line);

        $values = str_getcsv($line, $this->delimiter, $this->enclosure, $this->escape);

        // Map values to field names
        if ($this->headerCount === count($values)) {
            $row = array_combine($this->headers, $values) ?: [];
        } else {
            // The arrays have unequal length!
            $row = $values;
        }

        return $row;
    }

    /**
     * Get CSV headers.
     *
     * @param array $headers Headers
     *
     * @return array Save headers
     */
    private function getCsvHeaders(array $headers): array
    {
        $cache = [];

        foreach ($headers as $headerIndex => $header) {
            if (isset($cache[$header])) {
                // Prevent duplicate fields. Increment field name.
                $headers[$headerIndex] = $header . ++$cache[$header];
            } else {
                $cache[$header] = 1;
            }
        }

        return $headers;
    }
}
