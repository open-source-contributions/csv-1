<?php

namespace Selective\Csv;

use RuntimeException;
use Selective\Encoding\EncodingInterface;
use Selective\Encoding\IsoEncoding;

/**
 * Excel friendly CSV file writer.
 */
class CsvWriter
{
    /**
     * Encoding.
     *
     * @var EncodingInterface
     */
    protected $encoding;

    /**
     * Filename.
     *
     * @var string
     */
    protected $fileName = '';

    /**
     * Fields.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * Delimiter.
     *
     * @var string
     */
    protected $delimiter = ';';

    /**
     * Newline.
     *
     * @var string
     */
    protected $newline = "\r\n";

    /**
     * Enclosure.
     *
     * @var string
     */
    protected $enclosure = '"';

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
     * @param EncodingInterface $encoding encoding (utf8)
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
     * @param string $fileName fileName
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function setFileName(string $fileName)
    {
        if (empty($fileName)) {
            throw new RuntimeException('CSV filename required');
        }
        $this->fileName = $fileName;
        touch($this->fileName);
        chmod($this->fileName, 0777);
    }

    /**
     * Set delimiter.
     *
     * @param string $delimiter delimiter
     *
     * @return void
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    /**
     * Set newline.
     *
     * @param string $newline newline
     *
     * @return void
     */
    public function setNewline($newline)
    {
        $this->newline = $newline;
    }

    /**
     * Set enclosure.
     *
     * @param string $enclosure enclosure
     *
     * @return void
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * Put columns to file.
     *
     * @param array $columns Columns
     *
     * @return bool Status
     */
    public function putColumns(array $columns): bool
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

        $result = $this->putRow($row);
        $this->columns = $columns;

        return $result;
    }

    /**
     * Insert a single row to CSV file.
     *
     * @param array $row row
     *
     * @return bool Status
     */
    public function putRow($row)
    {
        return $this->putRows([$row]);
    }

    /**
     * Append rows to csv file.
     *
     * @param array $rows rows
     *
     * @return bool Status
     */
    public function putRows(array $rows)
    {
        $content = '';
        foreach ($rows as $row) {
            $content .= $this->rowToCsv($row);
        }
        $result = file_put_contents($this->fileName, $content, FILE_APPEND);

        return $result !== false;
    }

    /**
     * Convert row to CSV string.
     *
     * @param array $row row
     *
     * @return string csv
     */
    protected function rowToCsv($row)
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
     * @param string|null $value value
     * @param string $enclosure enclosure
     *
     * @return mixed escaped value
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
