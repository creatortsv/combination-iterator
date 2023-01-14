<?php

namespace Creatortsv\CombinationIterator\Tests;

use Creatortsv\CombinationIterator\CombinationIterator;
use Iterator;
use PHPUnit\Framework\TestCase;

/**
 * @template TMap of int[]
 */
class CombinationIteratorTest extends TestCase
{
    public function data(): Iterator
    {
        foreach (range(2, 4) as $count) {
            $maps = [];
            $combinations = $count ** $count;

            for ($i = 0; $i < $count; $i ++) {
                $maps[] = range(1, $count);;
            }

            yield $combinations . ' combinations' => [
                $count,
                $count ** $count,
                $maps,
            ];
        }
    }

    /**
     * @dataProvider data
     * @param TMap[] $maps
     */
    public function testCount(int $length, int $expectedCount, array $maps): void
    {
        $iterator = new CombinationIterator(...$maps);

        $this->assertSame($expectedCount, $iterator->count());
    }

    /**
     * @dataProvider data
     * @param TMap[] $maps
     */
    public function testIterate(int $length, int $expectedCount, array $maps): void
    {
        $iterator = new CombinationIterator(...$maps);
        $password = range(1, $length);
        $match = 0;

        shuffle($password);

        foreach ($iterator as $combination) {
            $this->assertCount($length, $combination);

            $combination === $password && $match ++ ;
        }

        $this->assertSame(1, $match);
    }
}
