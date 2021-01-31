<?php
use XlsxTest\XlsExchange;
use XlsxTest\XLSXHelper;
use XlsxTest\FTPSaver;
use XlsxTest\FileSaver;
use HermesMartins\EAN13\EAN13Validator;

require __DIR__.'/../vendor/autoload.php';

$help = "Usage: $argv[0] -i --infile=input_filename [-o --outfile=output_filename] [-h --ftp_host=host] "
       ."[-u --ftp_user=user]  [-p --ftp_pass=pass] [-d --ftp_dir=dir] [-s --strict_barcode_checking]\n";

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
$user    = $opt['ftp_user'] ?? $opt['u'] ?? 'anonymous';
$pass    = $opt['ftp_pass'] ?? $opt['p'] ?? '';
$dir     = $opt['ftp_dir']  ?? $opt['d'] ?? '/';
$barcode = isset($opt['strict_barcode_checking']) || isset($opt['s']);

if ($host) {
    $saver = new FTPSaver($host, $user, $pass, $dir);
    $outfile = basename($outfile);
} else {
    $saver = new FileSaver();
}
(new XlsExchange(new EAN13Validator(), new XLSXHelper(), $saver))
    ->stopOnBarcodeError($barcode)
    ->getOrder($infile)
    ->setOutputFile($outfile)
    ->export()
;