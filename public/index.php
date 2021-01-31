<?php
use BrisklyTest\XlsExchange;
use HermesMartins\EAN13\EAN13Validator;

require __DIR__.'/../vendor/autoload.php';

$EAN13Validator = new EAN13Validator();
$XlsExchange = new XlsExchange($EAN13Validator);

$XlsExchange
    ->setInputFile(__DIR__.'/../var/input/order.json')
    ->stopOnBarcodeError(false)
    ->export()
;