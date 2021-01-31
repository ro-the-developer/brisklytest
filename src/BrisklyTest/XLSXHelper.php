<?php
namespace BrisklyTest;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class XLSXHelper
{
    public Spreadsheet $spreadsheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    /**
     * Функция для базового фоматирования таблиц Excel
     * @param array $header массив, где ключ - это заголовок колонки, а значение - правила форматирования данных в ней
     * @param array $items данные для таблицы, двумерный массив
     *
     * Из методов форматирования пока доступен только forceNumber, предотвращает экспоненциальное представление чисел
     * @todo: добавить принудительное форматирование как текст
     * @todo: сделать размещение таблицы относительным, от переданных координат верхнего левого угла
     *
     * @return XLSXHelper
     */
    public function simpleTable( array $header, array $items): Spreadsheet
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
        return $this->spreadsheet;
    }
}