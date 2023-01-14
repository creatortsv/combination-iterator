<?php

namespace Creatortsv\CombinationIterator;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorIterator;
use SplDoublyLinkedList;
use Traversable;

/**
 * @template T
 * @template-implements Iterator<T[], T[]>
 */
class CombinationIterator implements Iterator, Countable
{
    private readonly SplDoublyLinkedList $iterator;

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

    public function getIterator(): SplDoublyLinkedList
    {
        return $this->iterator;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return array_product(array_map(iterator_count(...), iterator_to_array($this->iterator)));
    }

    /**
     * @inheritDoc
     * @return T[]
     */
    public function key(): array
    {
        return $this->apply(name: __FUNCTION__);
    }

    /**
     * @inheritDoc
     * @return T[]
     */
    public function current(): array
    {
        return $this->apply(name: __FUNCTION__);
    }

    public function next(): void
    {
        $this->modeSwitch();

        foreach ($this->iterator as $pos => $map) {
            $map->next();

            if ($map->valid() !== true) {
                $pos && $map->rewind();

                continue;
            }

            break;
        }

        $this->modeRead();
    }

    public function rewind(): void
    {
        $this->apply(name: __FUNCTION__);
    }

    public function valid(): bool
    {
        return !in_array(false, $this->apply(name: __FUNCTION__), strict: true);
    }

    /**
     * @return T[]
     */
    private function apply(string $name): array
    {
        return array_map(static fn (Iterator $iterator) => $iterator->$name() ?? true, iterator_to_array($this->iterator));
    }

    private function modeRead(): void
    {
        $this->iterator->setIteratorMode(
            mode: SplDoublyLinkedList::IT_MODE_FIFO | SplDoublyLinkedList::IT_MODE_KEEP,
        );
    }

    private function modeSwitch(): void
    {
        $this->iterator->setIteratorMode(
            mode: SplDoublyLinkedList::IT_MODE_LIFO | SplDoublyLinkedList::IT_MODE_KEEP,
        );
    }
}
