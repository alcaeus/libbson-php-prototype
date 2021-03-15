<?php

namespace MongoDB\BSON;

use FFI\CData;

/**
 * @internal
 */
interface FFIStub
{
    public function bson_init_from_json(CData $bson, string $data, int $len, CData $error): bool;

    public function bson_append_utf8(Cdata $bson, string $key, int $key_length, string $value, int $length_value);

    public function bson_as_json(CData $bson, CData $length): CData;

    public function bson_count_keys(CData $bson): int;

    public function bson_has_field(CData $bson, string $key): bool;
}
