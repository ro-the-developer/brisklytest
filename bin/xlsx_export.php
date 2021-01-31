<?php
use BrisklyTest\XlsExchange;
use BrisklyTest\XLSXHelper;
use BrisklyTest\FTPSaver;
use BrisklyTest\FileSaver;
use HermesMartins\EAN13\EAN13Validator;

require __DIR__.'/../vendor/autoload.php';

$help = "Usage: $argv[0] -i --infile=input_filename [-o --outfile=output_filename] [-h --ftp_host=host] "
       ."[-u --ftp_user=user]  [-p --ftp_pass=pass] [-d --ftp_dir=dir] [-s --strict_barcode_checking]";

$longopts  = [
    "infile:",
    "outfile:",
    "ftp_host:",
    "ftp_user:",
    "ftp_pass:",
    "ftp_dir:",
    "strict_barcode_checking::",
];
$opt = getopt("i:o:h:u:p:d:s::", $longopts);

$infile  = $opt['infile']   ?? $opt['i'] ?? die("Необходимо указать имя json файла c заказом\n$help");
$outfile = $opt['outfile']  ?? $opt['o'] ?? __DIR__."/../var/output/items.xlsx";
$host    = $opt['ftp_host'] ?? $opt['h'] ?? null;
$user    = $opt['ftp_user'] ?? $opt['u'] ?? null;
$pass    = $opt['ftp_pass'] ?? $opt['p'] ?? null;
$dir     = $opt['ftp_dir']  ?? $opt['d'] ?? null;
$barcode = isset($opt['strict_barcode_checking']) || isset($opt['s']);

if ($host) {
    $saver = new FTPSaver(...array_filter([$host, $user, $pass, $dir]));
} else {
    $saver = new FileSaver();
}

$XlsExchange = new XlsExchange(new EAN13Validator(), new XLSXHelper(), $saver);

$XlsExchange
    ->stopOnBarcodeError($barcode)
    ->getOrder($infile)
    ->setOutputFile($outfile)
    ->export()
;