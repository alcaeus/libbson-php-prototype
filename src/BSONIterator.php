<?php

namespace MongoDB\BSON;

use Closure;
use FFI;
use FFI\CData;
use MongoDB\BSON\FFI\LibBSON;

final class BSONIterator
{
    private static Closure $getBsonT;
    private CData $iter;

    public function __construct(private BSON $bson)
    {
        $this->iter = LibBSON::new('bson_iter_t');
        LibBSON::bson_iter_init(FFI::addr($this->iter), $this->getBsonT($bson));
    }

    public function find(string $key): bool
    {
        return LibBSON::bson_iter_find(FFI::addr($this->iter), $key);
    }

    public function utf8(): string
    {
        $length = FFI::new('uint32_t');

        return LibBSON::bson_iter_utf8(FFI::addr($this->iter), FFI::addr($length));
    }

    private function getBsonT(BSON $bson): CData
    {
        self::$getBsonT ??= Closure::bind(
            fn () => $this->data,
            $this,
            BSON::class,
        );

        return self::$getBsonT->call($bson);
    }
}
