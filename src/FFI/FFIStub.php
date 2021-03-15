<?php

namespace MongoDB\BSON\FFI;

use FFI\CData;

/**
 * @internal
 */
interface FFIStub
{
    public static function bson_append_utf8(Cdata $bson, string $key, int $key_length, string $value, int $length_value);

    public static function bson_as_json(CData $bson, CData $length): CData;

    public static function bson_count_keys(CData $bson): int;

    public static function bson_destroy(CData $bson): void;

    public static function bson_has_field(CData $bson, string $key): bool;

    public static function bson_init_from_json(CData $bson, string $data, int $len, CData $error): bool;

    public static function bson_iter_find(CData $iter, string $key): bool;

    public static function bson_iter_init(CData $iter, CData $bson): bool;

    public static function bson_iter_utf8(CData $iter, CData $length): string;

    public static function bson_new(): CData;
}
