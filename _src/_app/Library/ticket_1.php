<?php

require __DIR__ . '/ticket/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

try {
    $connector = new WindowsPrintConnector('ELGIN_i7');

    $printer = new Printer($connector);
    
    $printer->text("\nQWEQWEQW\n");
    $printer->text("\nQWEQWEQW\n");
    $printer->text("\nQWEQWEQW\n");
    
    $printer->cut();
$printer->close();
    
} catch (Exception $ex) {
    echo 'COULDM' . $ex->getMessage();
}
