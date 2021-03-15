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

extern bool
bson_init_from_json (bson_t *bson,
                     const char *data,
                     ssize_t len,
                     bson_error_t *error);

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

extern bool
bson_has_field (const bson_t *bson, const char *key);
