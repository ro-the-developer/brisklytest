<?php
namespace BrisklyTest;

use BrisklyTest\JsonHelper;
use HermesMartins\EAN13\EAN13Validator;

class XlsExchange
{
    protected array $order;
    protected array $items = [];
    protected bool $stopOnBarcodeError = true;

    protected string $inputFilename;
    protected string $outputFilename;
    protected string $ftpHost;
    protected string $ftpLogin;
    protected string $ftpPassword;
    protected string $ftpDir;
    protected EAN13Validator $EAN13Validator;

    public function __construct(EAN13Validator $EAN13Validator)
    {
        $this->EAN13Validator = $EAN13Validator;
    }

    public function setInputFile($filename)
    {
        $this->inputFilename = $filename;
        return $this;
    }

    public function stopOnBarcodeError(bool $stop)
    {
        $this->stopOnBarcodeError = $stop;
        return $this;
    }

    protected function getOrder()
    {
        $this->order = JsonHelper::fromFile($this->inputFilename, true);
        return $this;
    }

    protected function getItems()
    {
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
            $this->items[] = [
                'id' => $item['item']['id'],
                'barcode' => $item['item']['barcode'],
                'name' => $item['item']['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
            ];
        }
    }
    public function export()
    {
        $this->getOrder();
        $this->getItems();
        var_dump($this->items);
    }
}