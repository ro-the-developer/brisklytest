<?php
namespace XlsxTest;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FTPSaver implements XLSXSaverInterface
{
    protected string $host;
    protected string $username;
    protected string $password;
    protected string $dir;

    public function __construct($host, $username = 'anonymous', $password = '', $dir = '/')
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dir = $dir;
    }

    public function save(Spreadsheet $spreadsheet, string $destination): void
    {
        $filename = tempnam(sys_get_temp_dir(), 'xlsx');
        (new Xlsx($spreadsheet))->save($filename);
        $uri = "ftp://{$this->username}:{$this->password}@{$this->host}{$this->dir}";
        copy($filename, "$uri$destination");
        unlink($filename);
    }
}
