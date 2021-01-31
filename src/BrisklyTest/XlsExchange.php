<?php

namespace BrisklyTest;

use HermesMartins\EAN13\EAN13Validator;

class XlsExchange
{
    protected array $order;
    protected bool $stopOnBarcodeError = true;

    protected string $outputFilename;
    protected string $ftpHost;
    protected string $ftpLogin;
    protected string $ftpPassword;
    protected string $ftpDir;

    protected EAN13Validator $EAN13Validator;
    protected XLSXHelper $XSLXHelper;

    public function __construct(EAN13Validator $EAN13Validator, XLSXHelper $XSLXHelper)
    {
        $this->EAN13Validator = $EAN13Validator;
        $this->XSLXHelper = $XSLXHelper;
    }

    public function setOutputFile($filename)
    {
        $this->outputFilename = $filename;
        return $this;
    }

    public function stopOnBarcodeError(bool $stop)
    {
        $this->stopOnBarcodeError = $stop;
        return $this;
    }

    public function getOrder($filename)
    {
        $this->order = JsonHelper::fromFile($filename, true);
        return $this;
    }

    protected function getItems()
    {
        $items = [];
        foreach ($this->order['items'] as $item) {
            if (!$this->EAN13Validator->isAValidEAN13($item['item']['barcode'])) {
                if ($this->stopOnBarcodeError) {
                    throw new \InvalidArgumentException(
                        "Invalid barcode {$item['item']['barcode']} for item id {$item['item']['id']}"
                    );
                } else {
                    continue;
                }
            }
            $items[] = [
                'id' => $item['item']['id'],
                'barcode' => $item['item']['barcode'],
                'name' => $item['item']['name'],
                'quantity' => $item['quantity'],
                'total' => $item['quantity'] * $item['price'],
            ];
        }
        return $items;
    }
    public function export()
    {
        $items = $this->getItems();
        $header = ['ID' => [],
                   'ШК' => ['forceNumber' => true],
                   'Название' => [],
                   'Кол-во' => [],
                   'Сумма' => []
        ];
        $this->XSLXHelper->simpleTable($header, $items)->save($this->outputFilename);
    }
}