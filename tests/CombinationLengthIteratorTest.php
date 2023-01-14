<?php

namespace Creatortsv\CombinationIterator\Tests;

use Creatortsv\CombinationIterator\CombinationIterator;
use Creatortsv\CombinationIterator\CombinationLengthIterator;
use Exception;
use Iterator;
use PHPUnit\Framework\TestCase;

/**
 * @template TMap of array<array-key<int>, int>
 */
class CombinationLengthIteratorTest extends TestCase
{
    public function data(): Iterator
    {
        $length = 3;

        foreach (range(2, 4) as $count) {
            $combinations = $count ** $length;
            $maps = array_map(static fn (int $n): array => range(1, $n), array_fill(0, $count, $count));

            yield "Maps: $count, length: $length, combinations: $combinations" => [
                'expectedCount' => $combinations,
                'length' => $length,
                'maxVal' => $count,
                'maps' => $maps,
            ];
        }
    }

    /**
     * @dataProvider data
     * @param array<array-key<int>, TMap> $maps
     * @throws Exception
     */
    public function testCount(int $expectedCount, int $length, int $max, array $maps): void
    {
        $iterator = new CombinationLengthIterator(
            iterator: new CombinationIterator(...$maps),
            length: $length,
        );

        $this->assertSame($expectedCount, $iterator->count());
    }

    /**
     * @dataProvider data
     * @param array<array-key<int>, TMap> $maps
     * @throws Exception
     */
    public function testIterate(int $expectedCount, int $length, int $max, array $maps): void
    {
        $iterator = new CombinationLengthIterator(
            iterator: new CombinationIterator(...$maps),
            length: $length,
        );

        $input = $this->searching($length, $max);
        $match = 0;

        foreach ($iterator as $combination) {
            $this->assertCount($length, $combination);

            $combination === $input && $match ++ ;
        }

        $this->assertSame(1, $match);
    }

    /**
     * @return array<array-key<int>, int>
     */
    private function searching(int $length, int $max): array
    {
        $fn = function () use ($length, $max): Iterator {
            for ($i = 0; $i < $length; $i ++) {
                yield rand(1, $max);
            }
        };

        $result = iterator_to_array($fn());

        shuffle($result);

        return $result;
    }
}
