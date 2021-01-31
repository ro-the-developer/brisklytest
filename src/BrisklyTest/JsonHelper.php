<?php
namespace BrisklyTest;

class JsonHelper
{
    static function decode(string $json, ?bool $associative = null, int $depth = 512, int $flags = 0)
    {
        $decoded = json_decode($json, $associative, $depth, $flags);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }
        return $decoded;
    }

    static function fromFile($filename, ?bool $associative = null, int $depth = 512, int $flags = 0)
    {
        $json = file_get_contents($filename);
        return self::decode($json, $associative, $depth, $flags);
    }
}
