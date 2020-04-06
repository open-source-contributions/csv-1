# CSV

A Excel compatible CSV file reader and writer.

[![Latest Version on Packagist](https://img.shields.io/github/release/selective-php/csv.svg)](https://packagist.org/packages/selective/csv)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://github.com/selective-php/csv/workflows/php/badge.svg)](https://github.com/selective-php/csv/actions)
[![Coverage Status](https://scrutinizer-ci.com/g/selective-php/csv/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/selective-php/csv/code-structure)
[![Quality Score](https://scrutinizer-ci.com/g/selective-php/csv/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/selective-php/csv/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/selective/csv.svg)](https://packagist.org/packages/selective/csv/stats)

## Installation

```shell
composer require selective/csv
```

## Requirements

* PHP >= 7.1

## Reading a CSV file

```php
$csvReader = new \Selective\Csv\CsvReader();

// Optional settings
$csvReader->setDelimiter(';');
$csvReader->setEnclosure('"');
$csvReader->setNewline("\n");
$csvReader->setEscape("\\");

$content = file_get_contents('file.csv');
$csvReader->process($content);

while($row = $csvReader->fetch()) {
    var_dump($row);
}
```

## Writing a CSV file

```php
$outputFile = 'output.csv';

$csvWriter = new \Selective\Csv\CsvWriter($outputFile);

// Optional settings
$csvWriter->setDelimiter(';');
$csvWriter->setEnclosure('"');
$csvWriter->setNewline("\n");

// Insert columns
$csvWriter->putColumns([
    'id' => 'ID',
    'title' => 'Title',
]);

// Insert rows
$csvWriter->putRows([
    ['id' => 1, 'title' => 'Yes'],
    ['id' => 2, 'title' => 'No'],
]);
```

**Output**

```csv
"ID";"Title"
"1";"Yes"
"2";"No"
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Similar libraries

* [thephpleague/csv](https://github.com/thephpleague/csv)
