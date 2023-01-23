<?php

namespace Creatortsv\CombinationIterator;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorIterator;
use SplDoublyLinkedList;
use Traversable;

/**
 * @template TKey
 * @template TValue
 * @template-implements Iterator<int, array<int, TValue>>
 */
class CombinationIterator implements Iterator, Countable
{
    /**
     * @var SplDoublyLinkedList<int, Iterator<TKey, TValue>>
     */
    private readonly SplDoublyLinkedList $iterator;

    private int $iteration = 0;

    /**
     * @param iterable<TKey, TValue> $map
     * @param iterable<TKey, TValue> ...$maps
     */
    public function __construct(iterable $map, iterable ...$maps)
    {
        $this->iterator = new SplDoublyLinkedList();

        $maps = array_map(
            callback: static fn (iterable $map): Iterator => match (true) {
                $map instanceof Iterator => $map,
                $map instanceof Traversable => new IteratorIterator($map),
                default => new ArrayIterator($map),
            },
            array: [$map, ...$maps],
        );

        array_map($this->iterator->push(...), $maps);
    }

    /**
     * @return SplDoublyLinkedList<int, Iterator<TKey, TValue>>
     */
    public function getIterator(): SplDoublyLinkedList
    {
        return $this->iterator;
    }

    public function count(): int
    {
        return array_product(array_map(iterator_count(...), iterator_to_array($this->iterator)));
    }

    public function key(): int
    {
        return $this->iteration;
    }

    /**
     * @return array<int, TKey>
     */
    public function keys(): array
    {
        return array_map(static fn (Iterator $iterator) => $iterator->key(), iterator_to_array($this->iterator));
    }

    /**
     * @return array<int, TValue>
     */
    public function current(): array
    {
        return array_map(static fn (Iterator $iterator) => $iterator->current(), iterator_to_array($this->iterator));
    }

    public function next(): void
    {
        $this->iterator->setIteratorMode(mode: SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP);

        foreach ($this->iterator as $pos => $map) {
            $map->next();

            if ($map->valid() !== true) {
                $pos && $map->rewind();

                continue;
            }

            break;
        }

        $this->iteration ++ ;
        $this->iterator->setIteratorMode(mode: SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP);
    }

    public function rewind(): void
    {
        array_map(static fn (Iterator $iterator) => $iterator->rewind(), iterator_to_array($this->iterator));

        $this->iteration = 0;
    }

    public function valid(): bool
    {
        return !in_array(false, array_map(static fn (Iterator $iterator) => $iterator->valid(), iterator_to_array($this->iterator)), strict: true);
    }
}
