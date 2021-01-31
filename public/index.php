<?php
use BrisklyTest\XlsExchange;
use BrisklyTest\XLSXHelper;
use BrisklyTest\FTPSaver;
use BrisklyTest\FileSaver;
use HermesMartins\EAN13\EAN13Validator;

require __DIR__.'/../vendor/autoload.php';

#$saver = new FileSaver(); $output = __DIR__.'/../var/output/order.xlsx';
$saver = new FTPSaver('127.0.0.1'); $output = "ftp.xlsx";

$XlsExchange = new XlsExchange(new EAN13Validator(), new XLSXHelper(), $saver);

$XlsExchange
    ->stopOnBarcodeError(false)
    ->getOrder(__DIR__.'/../var/input/order.json')
    ->setOutputFile($output)
    ->export()
;