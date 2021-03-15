<?php

namespace MongoDB\BSON\Tests;

use MongoDB\BSON\BSON;
use MongoDB\BSON\BSONIterator;
use PHPUnit\Framework\TestCase;

class BSONIteratorTest extends TestCase
{
    public function testInstantiation(): void
    {
        $bson = BSON::createFromJson('{ "a": "foo" }');
        $iterator = new BSONIterator($bson);
        self::assertTrue($iterator->find('a'));
        self::assertSame('foo', $iterator->utf8());
    }
}
