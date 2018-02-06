# CSV
 
A Excel compatible CSV file reader and writer.

[![Latest Version on Packagist](https://img.shields.io/github/release/odan/csv.svg)](https://github.com/odan/csv/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://travis-ci.org/odan/csv.svg?branch=master)](https://travis-ci.org/odan/csv)
[![Coverage Status](https://scrutinizer-ci.com/g/odan/csv/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/odan/csv/code-structure)
[![Quality Score](https://scrutinizer-ci.com/g/odan/csv/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/odan/csv/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/odan/csv.svg)](https://packagist.org/packages/odan/csv)

## Installation

```shell
composer require odan/csv
```

## Requirementsant 

* PHP 7.x

## Reading a CSV file

```php
$csvReader = new \Odan\Csv\CsvReader();

$content = file_get_contents('file.csv');
$csvReader->process($content);

while($row = $csvReader->fetch()) {
    var_dump($row);
}
```

## Writing a CSV file

```php
$csvWriter = new \Odan\Csv\CsvWriter();

$file = 'output.csv';
$csvWriter->process($content);

$columns = [];
$columns['id'] = array('text' => __('ID'));
$columns['title'] = array('text' => __('My fancy title'));

$rows = [];
$rows[] = [
    ['id' => 1, 'title' => 'Yes'],
    ['id' => 2, 'title' => 'No'],
];

$csvWriter->putColumns($columns);

foreach ($rows as $row) {
    $csvWriter->putRow($row);
}
```

## Settings

```php
// todo
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

