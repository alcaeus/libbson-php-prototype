<?php

namespace MongoDB\BSON;

use Closure;
use FFI\CData;
use MongoDB\BSON\FFI\LibBSON;
use ReflectionClass;

abstract class BSONVisitor
{
    private const MAPPINGS = [
        'visitBefore' => 'visit_before',
        'visitAfter' => 'visit_after',
        'visitUtf8' => 'visit_utf8',
    ];

    private CData $visitorT;

    /**
     * @internal
     */
    final public function createVisitorT(): CData
    {
        if (isset($this->visitorT)) {
            return $this->visitorT;
        }

        $this->visitorT = LibBSON::new('bson_visitor_t');
        $classReflection = new ReflectionClass($this);
        foreach (self::MAPPINGS as $methodName => $fieldName) {
            $method = $classReflection->getMethod($methodName);
            if ($method->getDeclaringClass()->getName() === self::class) {
                continue;
            }

            $this->visitorT->$fieldName = $method->getClosure($this);
        }

        return $this->visitorT;
    }

    public function visitBefore(CData $iter, string $key): bool
    {
        return false;
    }

    public function visitAfter(CData $iter, string $key): bool
    {
        return false;
    }

    /* corrupt BSON, or unsupported type and visit_unsupported_type not set */
    public function visitCorrupt(CData $iter): void {}

    /* normal bson field callbacks */
    public function visitDouble(CData $iter, string $key, float $double): bool
    {
        return false;
    }

    public function visitUtf8(CData $iter, string $key, int $len, string $value): bool
    {
        return false;
    }

    public function visitDocument(CData $iter, string $key, BSON $document): bool
    {
        return false;
    }

//   bool (*visit_array) (const bson_iter_t *iter,
//                        const char *key,
//                        const bson_t *v_array,
//                        void *data);
//   bool (*visit_binary) (const bson_iter_t *iter,
//                         const char *key,
//                         bson_subtype_t v_subtype,
//                         size_t v_binary_len,
//                         const uint8_t *v_binary,
//                         void *data);
//   /* normal field with deprecated "Undefined" BSON type */
//   bool (*visit_undefined) (const bson_iter_t *iter,
//                            const char *key,
//                            void *data);
//   bool (*visit_oid) (const bson_iter_t *iter,
//                      const char *key,
//                      const bson_oid_t *v_oid,
//                      void *data);
//   bool (*visit_bool) (const bson_iter_t *iter,
//                       const char *key,
//                       bool v_bool,
//                       void *data);
//   bool (*visit_date_time) (const bson_iter_t *iter,
//                            const char *key,
//                            int64_t msec_since_epoch,
//                            void *data);
//   bool (*visit_null) (const bson_iter_t *iter, const char *key, void *data);
//   bool (*visit_regex) (const bson_iter_t *iter,
//                        const char *key,
//                        const char *v_regex,
//                        const char *v_options,
//                        void *data);
//   bool (*visit_dbpointer) (const bson_iter_t *iter,
//                            const char *key,
//                            size_t v_collection_len,
//                            const char *v_collection,
//                            const bson_oid_t *v_oid,
//                            void *data);
//   bool (*visit_code) (const bson_iter_t *iter,
//                       const char *key,
//                       size_t v_code_len,
//                       const char *v_code,
//                       void *data);
//   bool (*visit_symbol) (const bson_iter_t *iter,
//                         const char *key,
//                         size_t v_symbol_len,
//                         const char *v_symbol,
//                         void *data);
//   bool (*visit_codewscope) (const bson_iter_t *iter,
//                             const char *key,
//                             size_t v_code_len,
//                             const char *v_code,
//                             const bson_t *v_scope,
//                             void *data);
//   bool (*visit_int32) (const bson_iter_t *iter,
//                        const char *key,
//                        int32_t v_int32,
//                        void *data);
//   bool (*visit_timestamp) (const bson_iter_t *iter,
//                            const char *key,
//                            uint32_t v_timestamp,
//                            uint32_t v_increment,
//                            void *data);
//   bool (*visit_int64) (const bson_iter_t *iter,
//                        const char *key,
//                        int64_t v_int64,
//                        void *data);
//   bool (*visit_maxkey) (const bson_iter_t *iter, const char *key, void *data);
//   bool (*visit_minkey) (const bson_iter_t *iter, const char *key, void *data);
//   /* if set, called instead of visit_corrupt when an apparently valid BSON
//    * includes an unrecognized field type (reading future version of BSON) */
//   void (*visit_unsupported_type) (const bson_iter_t *iter,
//                                   const char *key,
//                                   uint32_t type_code,
//                                   void *data);
//   bool (*visit_decimal128) (const bson_iter_t *iter,
//                             const char *key,
//                             const bson_decimal128_t *v_decimal128,
//                             void *data);
}
