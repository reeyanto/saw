# SAW Library

## Instalasi

Untuk memasang library ini melalui Composer, jalankan perintah berikut di direktori project Anda:

```bash
composer require reeyanto/saw
```

Jika Anda belum memiliki file `composer.json`, jalankan:

```bash
composer init
```

lalu ikuti instruksi untuk membuat file `composer.json`.

## Contoh Penggunaan

Berikut contoh dasar penggunaan library ini di file PHP:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Reeyanto\SAW\SAW;

$saw = new SAW();
$saw->addCriteria('Harga', 0.2, 'cost');
$saw->addCriteria('Kualitas', 0.5, 'benefit');
$saw->addCriteria('Fitur', 0.3, 'benefit');

$saw->addAlternative('Produk A', ['Harga' => 100, 'Kualitas' => 80, 'Fitur' => 70]);
$saw->addAlternative('Produk B', ['Harga' => 150, 'Kualitas' => 90, 'Fitur' => 80]);
$saw->addAlternative('Produk C', ['Harga' => 120, 'Kualitas' => 85, 'Fitur' => 75]);

$result = $saw->calculate();
print_r($result);
```

## Output Contoh

```bash
Array
(
    [1] => Array
        (
            [alternative] => Produk B
            [score] => 0.9333
            [rank] => 1
        )

    [2] => Array
        (
            [alternative] => Produk C
            [score] => 0.9201
            [rank] => 2
        )

    [3] => Array
        (
            [alternative] => Produk A
            [score] => 0.9069
            [rank] => 3
        )

)
```

## Catatan

Pastikan Composer sudah terinstal di sistem Anda sebelum menjalankan perintah instalasi.
