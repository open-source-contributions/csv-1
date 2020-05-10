<?php

namespace Selective\Csv;

use InvalidArgumentException;
use RuntimeException;
use Selective\Encoding\EncodingInterface;
use Selective\Encoding\IsoEncoding;

/**
 * Excel friendly CSV file writer.
 */
final class CsvWriter
{
    /**
     * Encoding.
     *
     * @var EncodingInterface
     */
    private $encoding;

    /**
     * Filename.
     *
     * @var string
     */
    private $fileName = '';

    /**
     * Fields.
     *
     * @var array<mixed>
     */
    private $columns = [];

    /**
     * Delimiter.
     *
     * @var string
     */
    private $delimiter = ';';

    /**
     * Newline.
     *
     * @var string
     */
    private $newline = "\r\n";

    /**
     * Enclosure.
     *
     * @var string
     */
    private $enclosure = '"';

    /**
     * Constructor.
     *
     * @param string|null $fileName The CSV filename (optional)
     */
    public function __construct(string $fileName = null)
    {
        if ($fileName !== null) {
            $this->setFileName($fileName);
        }

        $this->setEncoding(new IsoEncoding());
    }

    /**
     * Set encoding.
     *
     * @param EncodingInterface $encoding The encoding (utf8)
     *
     * @return void
     */
    public function setEncoding(EncodingInterface $encoding): void
    {
        $this->encoding = $encoding;
    }

    /**
     * Set filename.
     *
     * @param string $fileName The fileName
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function setFileName(string $fileName): void
    {
        if (empty($fileName)) {
            throw new InvalidArgumentException('CSV filename required');
        }
        $this->fileName = $fileName;
        touch($this->fileName);
        chmod($this->fileName, 0777);
    }

    /**
     * Set delimiter.
     *
     * @param string $delimiter The delimiter
     *
     * @return void
     */
    public function setDelimiter(string $delimiter): void
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Set newline.
     *
     * @param string $newline The newline
     *
     * @return void
     */
    public function setNewline(string $newline): void
    {
        $this->newline = $newline;
    }

    /**
     * Set enclosure.
     *
     * @param string $enclosure The enclosure
     *
     * @return void
     */
    public function setEnclosure(string $enclosure): void
    {
        $this->enclosure = $enclosure;
    }

    /**
     * Put columns to file.
     *
     * @param array<mixed> $columns The columns
     *
     * @return void
     */
    public function putColumns(array $columns): void
    {
        $this->columns = [];
        $row = [];

        foreach ($columns as $fields) {
            if (isset($fields['text'])) {
                $row[] = $fields['text'];
            } else {
                $row[] = $fields;
            }
        }

        $this->putRow($row);
        $this->columns = $columns;
    }

    /**
     * Insert a single row to CSV file.
     *
     * @param array<mixed> $row The row
     *
     * @return void
     */
    public function putRow(array $row): void
    {
        $this->putRows([$row]);
    }

    /**
     * Append rows to csv file.
     *
     * @param array<mixed> $rows The rows
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function putRows(array $rows): void
    {
        $content = '';
        foreach ($rows as $row) {
            $content .= $this->rowToCsv($row);
        }
        $result = file_put_contents($this->fileName, $content, FILE_APPEND);

        if ($result === false) {
            throw new RuntimeException(sprintf('Failed to write file: %s', $this->fileName));
        }
    }

    /**
     * Convert row to CSV string.
     *
     * @param array<mixed> $row The row
     *
     * @return string The csv line
     */
    private function rowToCsv(array $row): string
    {
        if (!empty($this->columns)) {
            // Mapping of columns to the correct position
            $csvRows = [];
            foreach ($this->columns as $colIdx => $colValue) {
                $rowValue = '';
                if (isset($row[$colIdx])) {
                    $rowValue = $this->escape($row[$colIdx], $this->enclosure);
                }
                $csvRows[] = $rowValue;
            }
            $result = implode($this->delimiter, $csvRows) . $this->newline;
        } else {
            // no mapping
            foreach ($row as $index => $rowValue) {
                $row[$index] = $this->escape($rowValue, $this->enclosure);
            }
            $result = implode($this->delimiter, $row) . $this->newline;
        }

        return $result;
    }

    /**
     * Escape/quote a value to CSV string.
     *
     * @param string|null $value The value
     * @param string $enclosure The enclosure
     *
     * @return mixed The escaped value
     */
    public function escape($value, $enclosure = '"')
    {
        if ($value === null || $value === '') {
            return $enclosure . $enclosure;
        }
        $result = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $value) . $enclosure;
        $result = $this->encoding->encode($result);

        return $result;
    }
}
