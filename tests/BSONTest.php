<?php

namespace MongoDB\BSON\Tests;

use MongoDB\BSON\BSON;
use PHPUnit\Framework\TestCase;

class BSONTest extends TestCase
{
    public function testInstantiation(): void
    {
        $bson = BSON::createFromJson('{ "a": "foo" }');
        self::assertSame('{ "a" : "foo" }', (string) $bson);
        self::assertSame(1, $bson->countKeys());
        self::assertTrue($bson->hasField('a'));
        self::assertFalse($bson->hasField('b'));

        self::assertTrue($bson->appendUtf8('b', 'bar'));
        self::assertSame('{ "a" : "foo", "b" : "bar" }', (string) $bson);
        self::assertTrue($bson->hasField('b'));
    }
}
