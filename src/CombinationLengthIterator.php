<?php

declare(strict_types=1);

namespace Creatortsv\CombinationIterator;

use Countable;
use Exception;
use InvalidArgumentException;
use Iterator;
use SplDoublyLinkedList;

/**
 * @template TKey
 * @template TValue
 * @template-implements Iterator<int, array<int, TValue>>
 */
class CombinationLengthIterator implements Iterator, Countable
{
    /**
     * @throws Exception
     */
    public function __construct(
        private readonly CombinationIterator $iterator,
        public readonly int $length,
    ) {
        $this->iterator->rewind();
        $this->length < 1 && throw new InvalidArgumentException(
            message: 'Combination length must be greater tha 0',
        );

        $length = iterator_count($this->iterator->getIterator());
        $first = $this->iterator->getIterator()->offsetGet(0);

        if ($this->length < $length) {
            for (;$length > $this->length; $length --) {
                $this->iterator->getIterator()->pop();
            }
        } else {
            for (;$length < $this->length; $length ++) {
                $this->iterator->getIterator()->push(clone $first);
            }
        }
    }

    public function key(): int
    {
        return $this->iterator->key();
    }

    /**
     * @return array<int, TKey>
     */
    public function keys(): array
    {
        return $this->iterator->keys();
    }

    /**
     * @return array<int, TValue>
     */
    public function current(): array
    {
        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    /**
     * @return SplDoublyLinkedList<int, Iterator<TKey, TValue>>
     */
    public function getIterator(): SplDoublyLinkedList
    {
        return $this->iterator->getIterator();
    }

    public function count(): int
    {
        return $this->iterator->count();
    }
}
