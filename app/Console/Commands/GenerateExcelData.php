<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Faker\Factory as Faker;

class GenerateExcelData extends Command
{
    protected $signature = 'generate:excel';
    protected $description = 'Generate Excel file with sample data from all tables';

    public function handle()
    {
        $faker = Faker::create('es_ES');
        
        // Import PHPOffice libraries
        require_once base_path('vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Autoloader.php');
        \PhpOffice\PhpSpreadsheet\Autoloader::register();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        // Create sheets for each table
        $this->createHotelsSheet($spreadsheet, $faker);
        $this->createGuestsSheet($spreadsheet, $faker);
        $this->createRoomsSheet($spreadsheet, $faker);
        $this->createReservationsSheet($spreadsheet, $faker);
        $this->createPaymentsSheet($spreadsheet, $faker);
        $this->createBookingsSheet($spreadsheet, $faker);
        $this->createInventoriesSheet($spreadsheet, $faker);
        $this->createVehiclesSheet($spreadsheet, $faker);

        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'database_sample_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path($fileName);
        $writer->save($filePath);

        $this->info("Excel file created: " . $filePath);
    }

    private function createHotelsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Hotels');

        $headers = ['ID', 'Nombre', 'Dirección', 'Teléfono', 'Email', 'Descripción', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        for ($i = 1; $i <= 100; $i++) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $faker->company());
            $sheet->setCellValue('C' . $row, $faker->address());
            $sheet->setCellValue('D' . $row, $faker->phoneNumber());
            $sheet->setCellValue('E' . $row, $faker->email());
            $sheet->setCellValue('F' . $row, $faker->sentence());
            $sheet->setCellValue('G' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('H' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createGuestsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Guests');

        $headers = ['ID', 'Nombre', 'Email', 'Teléfono', 'Número de Documento', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        for ($i = 1; $i <= 100; $i++) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $faker->name());
            $sheet->setCellValue('C' . $row, $faker->email());
            $sheet->setCellValue('D' . $row, $faker->phoneNumber());
            $sheet->setCellValue('E' . $row, $faker->numerify('###########'));
            $sheet->setCellValue('F' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('G' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createRoomsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Rooms');

        $headers = ['ID', 'Hotel ID', 'Número', 'Tipo', 'Precio', 'Estado', 'Notas', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $statuses = ['available', 'occupied', 'maintenance'];
        $types = ['Single', 'Double', 'Suite', 'Deluxe', 'Penthouse'];

        for ($i = 1; $i <= 100; $i++) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, $i . 'A');
            $sheet->setCellValue('D' . $row, $faker->randomElement($types));
            $sheet->setCellValue('E' . $row, rand(50, 500));
            $sheet->setCellValue('F' . $row, $faker->randomElement($statuses));
            $sheet->setCellValue('G' . $row, $faker->sentence());
            $sheet->setCellValue('H' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('I' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createReservationsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Reservations');

        $headers = ['ID', 'Hotel ID', 'Room ID', 'Guest ID', 'Check-in', 'Check-out', 'Total', 'Estado', 'Notas', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $statuses = ['booked', 'checked_in', 'checked_out', 'cancelled'];

        for ($i = 1; $i <= 100; $i++) {
            $checkinDate = $faker->dateTimeBetween('-6 months', '+6 months');
            $checkoutDate = clone $checkinDate;
            $checkoutDate->modify('+' . rand(1, 7) . ' days');

            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, rand(1, 100));
            $sheet->setCellValue('D' . $row, rand(1, 100));
            $sheet->setCellValue('E' . $row, $checkinDate->format('Y-m-d'));
            $sheet->setCellValue('F' . $row, $checkoutDate->format('Y-m-d'));
            $sheet->setCellValue('G' . $row, rand(100, 1000));
            $sheet->setCellValue('H' . $row, $faker->randomElement($statuses));
            $sheet->setCellValue('I' . $row, $faker->sentence());
            $sheet->setCellValue('J' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('K' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createPaymentsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Payments');

        $headers = ['ID', 'Reservation ID', 'Monto', 'Método', 'ID Transacción', 'Nombre Pagador', 'Notas', 'User ID', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $methods = ['efectivo', 'tarjeta', 'transferencia', 'cheque'];

        for ($i = 1; $i <= 100; $i++) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, rand(50, 1000));
            $sheet->setCellValue('D' . $row, $faker->randomElement($methods));
            $sheet->setCellValue('E' . $row, $faker->uuid());
            $sheet->setCellValue('F' . $row, $faker->name());
            $sheet->setCellValue('G' . $row, $faker->sentence());
            $sheet->setCellValue('H' . $row, rand(1, 100));
            $sheet->setCellValue('I' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('J' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createBookingsSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Bookings');

        $headers = ['ID', 'User ID', 'Room ID', 'Check-in', 'Check-out', 'Estado', 'Total', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $statuses = ['reserved', 'checked_in', 'completed', 'cancelled'];

        for ($i = 1; $i <= 100; $i++) {
            $checkinDate = $faker->dateTimeBetween('-6 months', '+6 months');
            $checkoutDate = clone $checkinDate;
            $checkoutDate->modify('+' . rand(1, 7) . ' days');

            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, rand(1, 100));
            $sheet->setCellValue('D' . $row, $checkinDate->format('Y-m-d'));
            $sheet->setCellValue('E' . $row, $checkoutDate->format('Y-m-d'));
            $sheet->setCellValue('F' . $row, $faker->randomElement($statuses));
            $sheet->setCellValue('G' . $row, rand(100, 1000));
            $sheet->setCellValue('H' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('I' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createInventoriesSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Inventories');

        $headers = ['ID', 'Hotel ID', 'Artículo', 'Cantidad', 'Ubicación', 'Notas', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $items = ['Almohadas', 'Sábanas', 'Toallas', 'Vasos', 'Platos', 'Cucharas', 'Cuchillos', 'Tenedores', 'Lámparas', 'Cortinas'];

        for ($i = 1; $i <= 100; $i++) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, $faker->randomElement($items));
            $sheet->setCellValue('D' . $row, rand(10, 500));
            $sheet->setCellValue('E' . $row, 'Piso ' . rand(1, 10) . ', Sala ' . rand(1, 5));
            $sheet->setCellValue('F' . $row, $faker->sentence());
            $sheet->setCellValue('G' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('H' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function createVehiclesSheet($spreadsheet, $faker)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Vehicles');

        $headers = ['ID', 'Reservation ID', 'Placa', 'Marca', 'Modelo', 'Color', 'Estado', 'Fecha Entrada', 'Fecha Salida', 'Lugar Estacionamiento', 'Notas', 'Creado', 'Actualizado'];
        $sheet->fromArray([$headers], null, 'A1');

        $row = 2;
        $statuses = ['parking', 'left'];
        $brands = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Audi', 'Nissan', 'Hyundai', 'Kia'];
        $colors = ['Blanco', 'Negro', 'Plata', 'Rojo', 'Azul', 'Verde', 'Gris', 'Oro'];

        for ($i = 1; $i <= 100; $i++) {
            $entryDate = $faker->dateTimeBetween('-3 months', 'now');
            $exitDate = clone $entryDate;
            $exitDate->modify('+' . rand(1, 7) . ' days');

            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, rand(1, 100));
            $sheet->setCellValue('C' . $row, strtoupper($faker->bothify('??-###-??')));
            $sheet->setCellValue('D' . $row, $faker->randomElement($brands));
            $sheet->setCellValue('E' . $row, $faker->word());
            $sheet->setCellValue('F' . $row, $faker->randomElement($colors));
            $sheet->setCellValue('G' . $row, $faker->randomElement($statuses));
            $sheet->setCellValue('H' . $row, $entryDate->format('Y-m-d H:i:s'));
            $sheet->setCellValue('I' . $row, $exitDate->format('Y-m-d H:i:s'));
            $sheet->setCellValue('J' . $row, 'Piso ' . rand(1, 5) . ', Espacio ' . rand(1, 50));
            $sheet->setCellValue('K' . $row, $faker->sentence());
            $sheet->setCellValue('L' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $sheet->setCellValue('M' . $row, $faker->dateTime()->format('Y-m-d H:i:s'));
            $row++;
        }

        $this->formatSheet($sheet);
    }

    private function formatSheet($sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $sheet->getStyle('1:1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('1:1')->getFill()->getStartColor()->setARGB('FFD3D3D3');

        foreach ($sheet->getColumnIterator() as $column) {
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }
    }
}
