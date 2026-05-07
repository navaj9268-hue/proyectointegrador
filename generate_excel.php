<?php

// Descargar librería PHPSpreadsheet si no existe
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\PatternFill;
use Faker\Factory as Faker;

$faker = Faker::create('es_ES');

// Crear workbook
$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);

// Estilos
$headerFill = new PatternFill();
$headerFill->setFillType(PatternFill::FILL_SOLID);
$headerFill->getStartColor()->setARGB('FFD3D3D3');

$headerFont = new Font();
$headerFont->setBold(true);

function formatSheet($sheet, $headerFill, $headerFont) {
    // Formatear encabezados
    foreach ($sheet->getRowIterator(1, 1) as $row) {
        foreach ($row->getCellIterator() as $cell) {
            if ($cell->getValue()) {
                $cell->getStyle()->setFill($headerFill);
                $cell->getStyle()->setFont($headerFont);
            }
        }
    }
    
    // Auto-ajustar columnas
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
}

// TABLA HOTELS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Hotels');
$sheet->fromArray(['ID', 'Nombre', 'Dirección', 'Teléfono', 'Email', 'Descripción', 'Creado', 'Actualizado']);

for ($i = 1; $i <= 100; $i++) {
    $sheet->appendRow([
        $i,
        $faker->company(),
        $faker->address(),
        $faker->phoneNumber(),
        $faker->email(),
        $faker->sentence(),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA GUESTS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Guests');
$sheet->fromArray(['ID', 'Nombre', 'Email', 'Teléfono', 'Número de Documento', 'Creado', 'Actualizado']);

for ($i = 1; $i <= 100; $i++) {
    $sheet->appendRow([
        $i,
        $faker->name(),
        $faker->email(),
        $faker->phoneNumber(),
        $faker->numerify('###########'),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA ROOMS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Rooms');
$sheet->fromArray(['ID', 'Hotel ID', 'Número', 'Tipo', 'Precio', 'Estado', 'Notas', 'Creado', 'Actualizado']);

$roomTypes = ['Single', 'Double', 'Suite', 'Deluxe', 'Penthouse'];
$roomStatuses = ['available', 'occupied', 'maintenance'];

for ($i = 1; $i <= 100; $i++) {
    $sheet->appendRow([
        $i,
        rand(1, 100),
        $i . 'A',
        $roomTypes[array_rand($roomTypes)],
        rand(50, 500),
        $roomStatuses[array_rand($roomStatuses)],
        $faker->sentence(),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA RESERVATIONS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Reservations');
$sheet->fromArray(['ID', 'Hotel ID', 'Room ID', 'Guest ID', 'Check-in', 'Check-out', 'Total', 'Estado', 'Notas', 'Creado', 'Actualizado']);

$statuses = ['booked', 'checked_in', 'checked_out', 'cancelled'];

for ($i = 1; $i <= 100; $i++) {
    $checkin = $faker->dateTimeBetween('-6 months', '+6 months');
    $checkout = clone $checkin;
    $checkout->modify('+' . rand(1, 7) . ' days');
    
    $sheet->appendRow([
        $i,
        rand(1, 100),
        rand(1, 100),
        rand(1, 100),
        $checkin->format('Y-m-d'),
        $checkout->format('Y-m-d'),
        rand(100, 1000),
        $statuses[array_rand($statuses)],
        $faker->sentence(),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA PAYMENTS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Payments');
$sheet->fromArray(['ID', 'Reservation ID', 'Monto', 'Método', 'ID Transacción', 'Nombre Pagador', 'Notas', 'User ID', 'Creado', 'Actualizado']);

$methods = ['efectivo', 'tarjeta', 'transferencia', 'cheque'];

for ($i = 1; $i <= 100; $i++) {
    $sheet->appendRow([
        $i,
        rand(1, 100),
        rand(50, 1000),
        $methods[array_rand($methods)],
        $faker->uuid(),
        $faker->name(),
        $faker->sentence(),
        rand(1, 100),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA BOOKINGS
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Bookings');
$sheet->fromArray(['ID', 'User ID', 'Room ID', 'Check-in', 'Check-out', 'Estado', 'Total', 'Creado', 'Actualizado']);

$bookingStatuses = ['reserved', 'checked_in', 'completed', 'cancelled'];

for ($i = 1; $i <= 100; $i++) {
    $checkin = $faker->dateTimeBetween('-6 months', '+6 months');
    $checkout = clone $checkin;
    $checkout->modify('+' . rand(1, 7) . ' days');
    
    $sheet->appendRow([
        $i,
        rand(1, 100),
        rand(1, 100),
        $checkin->format('Y-m-d'),
        $checkout->format('Y-m-d'),
        $bookingStatuses[array_rand($bookingStatuses)],
        rand(100, 1000),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA INVENTORIES
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Inventories');
$sheet->fromArray(['ID', 'Hotel ID', 'Artículo', 'Cantidad', 'Ubicación', 'Notas', 'Creado', 'Actualizado']);

$items = ['Almohadas', 'Sábanas', 'Toallas', 'Vasos', 'Platos', 'Cucharas', 'Cuchillos', 'Tenedores', 'Lámparas', 'Cortinas'];

for ($i = 1; $i <= 100; $i++) {
    $sheet->appendRow([
        $i,
        rand(1, 100),
        $items[array_rand($items)],
        rand(10, 500),
        'Piso ' . rand(1, 10) . ', Sala ' . rand(1, 5),
        $faker->sentence(),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// TABLA VEHICLES
$sheet = $spreadsheet->createSheet();
$sheet->setTitle('Vehicles');
$sheet->fromArray(['ID', 'Reservation ID', 'Placa', 'Marca', 'Modelo', 'Color', 'Estado', 'Fecha Entrada', 'Fecha Salida', 'Lugar Estacionamiento', 'Notas', 'Creado', 'Actualizado']);

$vehicleStatuses = ['parking', 'left'];
$brands = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Audi', 'Nissan', 'Hyundai', 'Kia'];
$colors = ['Blanco', 'Negro', 'Plata', 'Rojo', 'Azul', 'Verde', 'Gris', 'Oro'];

for ($i = 1; $i <= 100; $i++) {
    $entry = $faker->dateTimeBetween('-3 months', 'now');
    $exit = clone $entry;
    $exit->modify('+' . rand(1, 7) . ' days');
    
    $sheet->appendRow([
        $i,
        rand(1, 100),
        strtoupper($faker->bothify('??-###-??')),
        $brands[array_rand($brands)],
        $faker->word(),
        $colors[array_rand($colors)],
        $vehicleStatuses[array_rand($vehicleStatuses)],
        $entry->format('Y-m-d H:i:s'),
        $exit->format('Y-m-d H:i:s'),
        'Piso ' . rand(1, 5) . ', Espacio ' . rand(1, 50),
        $faker->sentence(),
        $faker->dateTime()->format('Y-m-d H:i:s'),
        $faker->dateTime()->format('Y-m-d H:i:s')
    ]);
}
formatSheet($sheet, $headerFill, $headerFont);

// Guardar archivo en Descargas
$downloadsPath = $_SERVER['HOMEDRIVE'] . $_SERVER['HOMEPATH'] . '\Downloads';
$filename = 'database_sample_' . date('Ymd_His') . '.xlsx';
$filePath = $downloadsPath . '\\' . $filename;

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($filePath);

echo "✓ Archivo Excel creado exitosamente en: " . $filePath . "\n";
?>
