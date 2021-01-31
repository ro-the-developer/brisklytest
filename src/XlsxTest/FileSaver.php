<?php
namespace XlsxTest;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FileSaver implements XLSXSaverInterface
{
    public function save(Spreadsheet $spreadsheet, string $destination): void
    {
        (new Xlsx($spreadsheet))->save($destination);
    }
}
