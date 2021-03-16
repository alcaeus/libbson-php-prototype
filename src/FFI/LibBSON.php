<?php

namespace MongoDB\BSON\FFI;

use FFI;
use FFI\CData;
use function strlen;

final class LibBSON
{
    private static $ffi;

    private function __construct()
    {
    }

    public static function new($type, bool $owned = true, bool $persistent = false): CData
    {
        return self::ffi()->new($type, $owned, $persistent);
    }

    public static function bson_append_utf8(CData $bson, string $key, int $key_length, string $value, int $length_value)
    {
        return self::ffi()->bson_append_utf8($bson, $key, strlen($key), $value, strlen($value));
    }

    public static function bson_as_json(CData $bson, CData $length): CData
    {
        return self::ffi()->bson_as_json($bson, FFI::addr($length));
    }

    public static function bson_count_keys(CData $bson): int
    {
        return self::ffi()->bson_count_keys($bson);
    }

    public static function bson_destroy(CData $bson): void
    {
        self::ffi()->bson_destroy($bson);
    }

    public static function bson_has_field(CData $bson, string $key): bool
    {
        return self::ffi()->bson_has_field($bson, $key);
    }

    public static function bson_init_from_json(CData $bson, string $data, int $len, CData $error): bool
    {
        return self::ffi()->bson_init_from_json($bson, $data, $len, $error);
    }

    public static function bson_iter_find(CData $iter, string $key): bool
    {
        return self::ffi()->bson_iter_find($iter, $key);
    }

    public static function bson_iter_init(CData $iter, CData $bson): bool
    {
        return self::ffi()->bson_iter_init($iter, $bson);
    }

    public static function bson_iter_utf8(CData $iter, CData $length): string
    {
        return self::ffi()->bson_iter_utf8($iter, $length);
    }

    public static function bson_iter_visit_all(CData $iter, CData $visitor, ?CData $data): bool
    {
        return self::ffi()->bson_iter_visit_all($iter, $visitor, $data);
    }

    public static function bson_new(): CData
    {
        return self::ffi()->bson_new();
    }

    private static function ffi(): FFI | FFIStub
    {
        return self::$ffi ??= FFI::load(__DIR__ . '/libbson.h');
    }
}
