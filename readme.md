# PHP CPE Library

PHP library for working with CPE (Common Platform Enumeration)

## Installation

```bash
composer require rikstone/php-cpe
```

## Features

- Parse and build CPE URIs (2.2 and 2.3)

- Convert between versions (2.2 ↔ 2.3)

- Typed classes for each CPE version

- Field normalization according to MITRE rules

## Example

**Creating a CPE 2.2**

```php
use Rikstone\Cpe\Cpe22;
use Rikstone\Cpe\Part;

$cpe = (new Cpe22())
    ->setPart(Part::H)
    ->setVendor('dell')
    ->setProduct('inspiron')
    ->setVersion('8500');

echo $cpe;
```

**Creating a CPE 2.3**

```php
use Rikstone\Cpe\Cpe23;
use Rikstone\Cpe\Part;

$cpe = (new Cpe23())
    ->setPart(Part::A)
    ->setVendor('f5')
    ->setProduct('nginx')
    ->setVersion('0.3.31');
//    ->setUpdate()
//    ->setEdition()
//    ->setLanguage()
//    ->setSwEdition()
//    ->setTargetSw()
//    ->setTargetHw()
//    ->setOther();

echo $cpe;
// cpe:2.3:a:f5:nginx:0.3.31:*:*:*:*:*:*:*
```

**Parsing a CPE 2.2 string**

```php
$cpe = Cpe22::fromString('cpe:/a:zoom:zoom_client_for_vdi:5.7.1');

echo $cpe->getVersion(); // 5.7.1
```
**Converting between CPE versions**

**Convert 2.2 → 2.3**
```php
use Rikstone\Cpe\CpeConverter;
use Rikstone\Cpe\Cpe23;
use Rikstone\Cpe\Cpe22;

$source = Cpe22::fromString('cpe:/o:microsoft:windows_2000');

$cpe23 = (new Converter())->convert($source, Cpe23::class);

echo $cpe23; // cpe:2.3:o:microsoft:windows_2000:*:*:*:*:*:*:*:*
```
**Convert 2.3 → 2.2**

```php
use Rikstone\Cpe\CpeConverter;
use Rikstone\Cpe\Cpe23;
use Rikstone\Cpe\Cpe22;

$source = Cpe23::fromString('cpe:2.3:a:zoom:zoom_client_for_vdi:5.7.1:*:*:*:*:*:*:*');

$cpe22 = (new Converter())->convert($source, Cpe22::class);

echo $cpe22; // cpe:/a:zoom:zoom_client_for_vdi:5.7.1
```