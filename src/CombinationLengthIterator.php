<?php

declare(strict_types=1);

namespace Creatortsv\CombinationIterator;

use Countable;
use Exception;
use InvalidArgumentException;
use Iterator;
use Traversable;

/**
 * @template T
 * @template-implements Iterator<T[], T[]>
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

    /**
     * @inheritDoc
     * @return T[]
     */
    public function key(): array
    {
        return $this->iterator->key();
    }

    /**
     * @inheritDoc
     * @return T[]
     */
    public function current(): array
    {
        return $this->iterator->current();
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->iterator->next();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    public function getIterator(): Traversable
    {
        return $this->iterator->getIterator();
    }

    public function count(): int
    {
        return $this->iterator->count();
    }
}
