<?php

namespace App\Midjourney\Utils;

use Clue\React\Zlib\Decompressor;

class Zlib
{
    public function __construct(
        protected string $data
    ) {
    }

    public function compress()
    {
    }

    public function decompress($encoding)
    {
        // $encoding = ZLIB_ENCODING_GZIP; // or ZLIB_ENCODING_RAW or ZLIB_ENCODING_DEFLATE
        $decompressor = new Decompressor($encoding);
        $data = '';
        $decompressor->on('data', function ($buffer) use (&$data)  {
            $data .= $buffer;
        });
        $decompressor->write($this->data);
        return $data;
    }
}