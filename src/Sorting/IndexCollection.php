<?php

namespace Belca\Support\Sorting;

use Iterator;
use Countable;

class IndexCollection implements Iterator, Countable
{
    /**
     * The current position of the pointer.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * A collection of indexes.
     *
     * @var array|Index[]
     */
    protected $collection = [];

    /**
     * Rewinds to the first index.
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Returns a current index.
     *
     * @return Index|null
     */
    public function current()
    {
        return $this->collection[$this->position] ?? null;
    }

    /**
     * Returns a key of a current index.
     *
     * @return string|null
     */
    public function key()
    {
        /** @var Index|null $index **/
        $index = $this->current();

        return $index === null
            ? null
            : $index->getKey();
    }

    /**
     * Returns a position of a current index.
     *
     * @return int
     */
    public function position(): int
    {
        return $this->position;
    }

    /**
     * Forwards to the next index.
     *
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Checks if the iterator is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    /**
     * Returns a amount of indexes.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->collection);
    }

    /**
     * Inserts a new Index to the collection.
     *
     * @param  Index  $index
     * @return void
     */
    public function insert(Index $index)
    {
        $this->collection[$index->getKey()] = $index;
    }

    /**
     * Creates a new Index and adds its to the collection of indexes.
     *
     * @param string  $key        The key of the item.
     * @param int     $position   A position number of the item.
     * @param int     $priority   A priority of the item.
     * @param int     $direction  A direction of the item.
     * @param string  $nearbyKey  A nearby key of the item.
     * @return Index
     */
    public function create(string $key, int $position, int $priority = 0,
        int $direction = 0, string $nearbyKey = null)
    {
        $index = new Index($key, $position, $priority, $direction, $nearbyKey);

        $this->insert($index);

        return $index;
    }

    /**
     * Returns an Index by its key.
     *
     * @param  string $key
     * @return Index|null
     */
    public function get(string $key = null)
    {
        return $this->collection[$key] ?? null;
    }

    /**
     * Returns an Index by its position.
     *
     * @param  int  $position
     * @return Item|null
     */
    public function getByPosition(int $position)
    {
        return $this->collection[$position] ?? null;
    }

    /**
     * Returns the first Index from the collection.
     *
     * @return Item|null
     */
    public function first()
    {
        return reset($this->collection) ?: null;
    }

    /**
     * Returns the last Index from the collection.
     *
     * @return Item|null
     */
    public function last()
    {
        return end($this->collection) ?: null;
    }

    /**
     * Deletes an Index by a given key.
     *
     * @param  string $key
     * @return void
     */
    public function delete(string $key)
    {
        unset($this->collection[$key]);
    }

    /**
     * Deletes an Index by a given position.
     *
     * @param  int  $position
     * @return void
     */
    public function deleteByPosition(int $position)
    {
        unset($this->collection[$position]);
    }

    /**
     * Checks whether an Index exists by a given key.
     *
     * @param  string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return isset($this->collection[$key]);
    }

    /**
     * Returns keys of the indexes.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->collection);
    }

    /**
     * Returns this object in the form an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        foreach ($this->collection as $key => $index) {
            $array[$index->getKey()] = $index->toArray();
        }

        return $array ?? [];
    }
}
