<?php

namespace Selective\Csv;

use Selective\Encoding\EncodingInterface;
use Selective\Encoding\Utf8Encoding;

/**
 * CSV Reader.
 */
final class CsvReader
{
    /**
     * Encoding.
     *
     * @var EncodingInterface
     */
    private $encoding;

    /**
     * Delimiter.
     *
     * @var string
     */
    private $delimiter = ';';

    /**
     * Enclosure.
     *
     * @var string
     */
    private $enclosure = '"';

    /**
     * Escape.
     *
     * @var string
     */
    private $escape = '\\';

    /**
     * New line.
     *
     * @var string
     */
    private $newline = "\n";

    /**
     * Headers.
     *
     * @var array<mixed>
     */
    private $headers = [];

    /**
     * Header count.
     *
     * @var int
     */
    private $headerCount = 0;

    /**
     * Lines.
     *
     * @var array<mixed>
     */
    private $lines = [];

    /**
     * The constructor.
     */
    public function __construct()
    {
        $this->setEncoding(new Utf8Encoding());
    }

    /**
     * Set encoding.
     *
     * @param EncodingInterface $encoding The encoding
     *
     * @return void
     */
    public function setEncoding(EncodingInterface $encoding): void
    {
        $this->encoding = $encoding;
    }

    /**
     * Set delimiter.
     *
     * @param string $delimiter The delimiter char
     *
     * @return void
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Set enclosure.
     *
     * @param string $enclosure The enclosure char
     *
     * @return void
     */
    public function setEnclosure(string $enclosure): void
    {
        $this->enclosure = $enclosure;
    }

    /**
     * Set escape.
     *
     * @param string $escape The escape char
     *
     * @return void
     */
    public function setEscape(string $escape): void
    {
        $this->escape = $escape;
    }

    /**
     * Set newline.
     *
     * @param string $newline The newline char
     *
     * @return void
     */
    public function setNewline(string $newline): void
    {
        $this->newline = $newline;
    }

    /**
     * Process CSV content.
     *
     * @param string $csv CSV content
     *
     * @return void
     */
    public function process(string $csv): void
    {
        $this->lines = str_getcsv($csv, $this->newline);
        reset($this->lines);
        $this->headers = [];
        $this->headerCount = 0;
        $this->parseHeader();
    }

    /**
     * Parse header.
     *
     * @return void
     */
    private function parseHeader(): void
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
     * @return array<mixed>|null The next row or null
     */
    public function fetch(): ?array
    {
        $line = current($this->lines);

        if ($line === false) {
            return null;
        }

        next($this->lines);

        // Encode data
        $line = $this->encoding->encode($line) ?? '';

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
     * @param array<mixed> $headers Headers
     *
     * @return array<mixed> Save headers
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
