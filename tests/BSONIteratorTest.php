<?php

namespace MongoDB\BSON\Tests;

use FFI\CData;
use MongoDB\BSON\BSON;
use MongoDB\BSON\BSONIterator;
use MongoDB\BSON\BSONVisitor;
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

    public function testVisit(): void
    {
        $bson = BSON::createFromJson('{ "a": "foo" }');
        $iterator = new BSONIterator($bson);

        $visitor = new class extends BSONVisitor {
            private array $calls = [];

            public function visitBefore(CData $iter, string $key): bool
            {
                $this->calls[] = ['visitBefore' => ['key' => $key]];

                return false;
            }

            public function visitAfter(CData $iter, string $key): bool
            {
                $this->calls[] = ['visitAfter' => ['key' => $key]];

                return false;
            }

            public function visitUtf8(CData $iter, string $key, int $len, string $value): bool
            {
                $this->calls[] = ['visitUtf8' => ['key' => $key, 'len' => $len, 'value' => $value]];

                return false;
            }

            public function getCalls(): array
            {
                return $this->calls;
            }
        };

        self::assertFalse($iterator->visitAll($visitor));
        self::assertCount(3, $visitor->getCalls());
        self::assertSame(
            [
                ['visitBefore' => ['key' => 'a']],
                ['visitUtf8' => ['key' => 'a', 'len' => 3, 'value' => 'foo']],
                ['visitAfter' => ['key' => 'a']],
            ],
            $visitor->getCalls(),
        );
    }
}
