<?php
namespace BrisklyTest;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XLSXHelper
{
    public Spreadsheet $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function simpleTable($header, $items)
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->fromArray(array_merge([array_keys($header)], $items ));

        // apply header formatting
        $styleArrayFirstRow = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::VERTICAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($styleArrayFirstRow);

        // apply custom column formatting
        foreach (array_values($header) as $colNumber => $format) {
            if ($format['forceNumber'] ?? false) {
                $colString = Coordinate::stringFromColumnIndex($colNumber+1);
                $startCol = 2;
                $endCol = $startCol + count($items) - 1;
                $range = "$colString$startCol:$colString$endCol";
                $sheet->getStyle($range)->getNumberFormat()->setFormatCode('#');
            }
        }
        // apply autoSize
        foreach (range('A', $sheet->getHighestDataColumn()) as $col)
        {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        return $this;
    }

    public function save($filename)
    {
        (new Xlsx($this->spreadsheet))->save($filename);
    }
}