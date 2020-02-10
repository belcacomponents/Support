<?php

namespace Belca\Support\Sorting;

class Index
{
    /**
     * Creates a new Index.
     *
     * @param string  $key        The key of the item.
     * @param int     $position   A position number of the item.
     * @param int     $priority   A priority of the item.
     * @param int     $direction  A direction of the item.
     * @param string  $nearbyKey  A nearby key of the item.
     */
    public function __construct(string $key, int $position, int $priority = 0,
        int $direction = 0, string $nearbyKey = null)
    {
        $this->key = $key;
        $this->position = $position;
        $this->priority = $priority;
        $this->direction = $direction;
        $this->nearbyKey = $nearbyKey;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return int
     */
    public function getDirection(): int
    {
        return $this->direction;
    }

    /**
     * @return string|null
     */
    public function getNearbyKey()
    {
        return $this->nearbyKey;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'position' => $this->position,
            'priority' => $this->priority,
            'direction' => $this->direction,
            'nearby_key' => $this->nearbyKey,
        ];
    }
}
