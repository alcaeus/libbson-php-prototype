<?php

namespace MongoDB\BSON;

use FFI;
use FFI\CData;
use FFI\CType;
use Stringable;

final class BSON implements Stringable
{
    private static $ffi;
    private static $bson_t;

    private function __construct(private CData $data /* bson_t *data */) {}

    public function __toString()
    {
        $length = FFI::new('uint64_t');

        /** @var CData $json */
        $json = self::ffi()->bson_as_json($this->data, FFI::addr($length));
        try {
            return FFI::string($json);
        } finally {
            FFI::free($json);
        }
    }

    public static function createFromJson(string $json): self
    {
        $data = FFI::addr(self::ffi()->new(self::bson_t()));
        $error = null;

        self::ffi()->bson_init_from_json($data, $json, strlen($json), $error);

        return new self($data);
    }

    public function appendUtf8(string $key, string $value): bool
    {
        return self::ffi()->bson_append_utf8($this->data, $key, strlen($key), $value, strlen($value));
    }

    public function countKeys(): int
    {
        return self::ffi()->bson_count_keys($this->data);
    }

    public function hasField(string $key): bool
    {
        return self::ffi()->bson_has_field($this->data, $key);
    }

    private static function ffi(): FFI | FFIStub
    {
        return self::$ffi ??= FFI::load(__DIR__ . '/BSON/libbson.h');
    }

    private static function bson_t(): CType
    {
        return static::$bson_t ??= static::ffi()->type('bson_t');
    }
}
