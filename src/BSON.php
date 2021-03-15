<?php

namespace MongoDB\BSON;

use FFI;
use FFI\CData;
use MongoDB\BSON\FFI\LibBSON;
use Stringable;

final class BSON implements Stringable
{
    private function __construct(private CData $data /* bson_t *data */) {}

    public function __destruct()
    {
        LibBSON::bson_destroy($this->data);
    }

    public function __toString()
    {
        $length = FFI::new('uint64_t');

        /** @var CData $json */
        $json = LibBSON::bson_as_json($this->data, $length);
        try {
            return FFI::string($json);
        } finally {
            FFI::free($json);
        }
    }

    public static function createFromJson(string $json): self
    {
        $error = FFI::addr(LibBSON::new('bson_error_t'));
        $data = LibBSON::bson_new();

        if (!LibBSON::bson_init_from_json($data, $json, strlen($json), $error)) {
            throw new \Exception(FFI::string($error->message));
        }

        return new self($data);
    }

    public function appendUtf8(string $key, string $value): bool
    {
        return LibBSON::bson_append_utf8($this->data, $key, strlen($key), $value, strlen($value));
    }

    public function countKeys(): int
    {
        return LibBSON::bson_count_keys($this->data);
    }

    public function hasField(string $key): bool
    {
        return LibBSON::bson_has_field($this->data, $key);
    }
}
