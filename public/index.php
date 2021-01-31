<?php
use BrisklyTest\XlsExchange;
use BrisklyTest\XLSXHelper;
use HermesMartins\EAN13\EAN13Validator;

require __DIR__.'/../vendor/autoload.php';

$XlsExchange = new XlsExchange(new EAN13Validator(), new XLSXHelper());

$XlsExchange
    ->stopOnBarcodeError(false)
    ->getOrder(__DIR__.'/../var/input/order.json')
    ->setOutputFile(__DIR__.'/../var/output/order.xlsx')
    ->export()
;