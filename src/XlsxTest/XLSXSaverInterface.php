<?php
namespace XlsxTest;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

interface XLSXSaverInterface
{
    public function save( Spreadsheet  $spreadsheet, string $destination): void;
}
