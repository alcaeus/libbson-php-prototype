/* TODO: Path needs to be discovered with pkg-config or other tooling */
#define FFI_LIB "/usr/local/Cellar/mongo-c-driver/1.17.4/lib/libbson-1.0.dylib"
#define FFI_SCOPE "alcaeus/libbson-php-prototype"

#define BSON_ERROR_BUFFER_SIZE 504

typedef struct _bson_error_t {
   uint32_t domain;
   uint32_t code;
   char message[504 /* BSON_ERROR_BUFFER_SIZE */];
} bson_error_t;

typedef struct _bson_t {
   uint32_t flags;       /* Internal flags for the bson_t. */
   uint32_t len;         /* Length of BSON data. */
   uint8_t padding[120]; /* Padding for stack allocation. */
} bson_t;

typedef enum {
   BSON_TYPE_EOD = 0x00,
   BSON_TYPE_DOUBLE = 0x01,
   BSON_TYPE_UTF8 = 0x02,
   BSON_TYPE_DOCUMENT = 0x03,
   BSON_TYPE_ARRAY = 0x04,
   BSON_TYPE_BINARY = 0x05,
   BSON_TYPE_UNDEFINED = 0x06,
   BSON_TYPE_OID = 0x07,
   BSON_TYPE_BOOL = 0x08,
   BSON_TYPE_DATE_TIME = 0x09,
   BSON_TYPE_NULL = 0x0A,
   BSON_TYPE_REGEX = 0x0B,
   BSON_TYPE_DBPOINTER = 0x0C,
   BSON_TYPE_CODE = 0x0D,
   BSON_TYPE_SYMBOL = 0x0E,
   BSON_TYPE_CODEWSCOPE = 0x0F,
   BSON_TYPE_INT32 = 0x10,
   BSON_TYPE_TIMESTAMP = 0x11,
   BSON_TYPE_INT64 = 0x12,
   BSON_TYPE_DECIMAL128 = 0x13,
   BSON_TYPE_MAXKEY = 0x7F,
   BSON_TYPE_MINKEY = 0xFF,
} bson_type_t;

typedef struct {
   uint8_t bytes[12];
} bson_oid_t;

typedef enum {
   BSON_SUBTYPE_BINARY = 0x00,
   BSON_SUBTYPE_FUNCTION = 0x01,
   BSON_SUBTYPE_BINARY_DEPRECATED = 0x02,
   BSON_SUBTYPE_UUID_DEPRECATED = 0x03,
   BSON_SUBTYPE_UUID = 0x04,
   BSON_SUBTYPE_MD5 = 0x05,
   BSON_SUBTYPE_ENCRYPTED = 0x06,
   BSON_SUBTYPE_USER = 0x80,
} bson_subtype_t;

typedef struct {
   uint64_t low;
   uint64_t high;
} bson_decimal128_t;

typedef struct _bson_value_t {
   bson_type_t value_type;
   int32_t padding;
   union {
      bson_oid_t v_oid;
      int64_t v_int64;
      int32_t v_int32;
      int8_t v_int8;
      double v_double;
      bool v_bool;
      int64_t v_datetime;
      struct {
         uint32_t timestamp;
         uint32_t increment;
      } v_timestamp;
      struct {
         char *str;
         uint32_t len;
      } v_utf8;
      struct {
         uint8_t *data;
         uint32_t data_len;
      } v_doc;
      struct {
         uint8_t *data;
         uint32_t data_len;
         bson_subtype_t subtype;
      } v_binary;
      struct {
         char *regex;
         char *options;
      } v_regex;
      struct {
         char *collection;
         uint32_t collection_len;
         bson_oid_t oid;
      } v_dbpointer;
      struct {
         char *code;
         uint32_t code_len;
      } v_code;
      struct {
         char *code;
         uint8_t *scope_data;
         uint32_t code_len;
         uint32_t scope_len;
      } v_codewscope;
      struct {
         char *symbol;
         uint32_t len;
      } v_symbol;
      bson_decimal128_t v_decimal128;
   } value;
} bson_value_t;

typedef struct {
   const uint8_t *raw; /* The raw buffer being iterated. */
   uint32_t len;       /* The length of raw. */
   uint32_t off;       /* The offset within the buffer. */
   uint32_t type;      /* The offset of the type byte. */
   uint32_t key;       /* The offset of the key byte. */
   uint32_t d1;        /* The offset of the first data byte. */
   uint32_t d2;        /* The offset of the second data byte. */
   uint32_t d3;        /* The offset of the third data byte. */
   uint32_t d4;        /* The offset of the fourth data byte. */
   uint32_t next_off;  /* The offset of the next field. */
   uint32_t err_off;   /* The offset of the error. */
   bson_value_t value; /* Internal value for various state. */
} bson_iter_t;

extern bool
bson_append_utf8 (bson_t *bson,
                  const char *key,
                  int key_length,
                  const char *value,
                  int length);

extern char *
bson_as_json (const bson_t *bson, size_t *length);

extern uint32_t
bson_count_keys (const bson_t *bson);

extern void
bson_destroy (bson_t *bson);

extern bool
bson_has_field (const bson_t *bson, const char *key);

extern bool
bson_init_from_json (bson_t *bson,
                     const char *data,
                     ssize_t len,
                     bson_error_t *error);

extern bson_t *
bson_new (void);

extern bool
bson_iter_find (bson_iter_t *iter, const char *key);

extern bool
bson_iter_init (bson_iter_t *iter, const bson_t *bson);

extern const char *
bson_iter_utf8 (const bson_iter_t *iter, uint32_t *length);
