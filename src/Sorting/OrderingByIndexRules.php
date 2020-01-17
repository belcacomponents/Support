<?php

namespace Belca\Support\Sorting;

use Belca\Support\Arr;

class OrderingByIndexRules
{
    const LEFT_SIDE = -1;

    const RIGHT_SIDE = 1;

    /**
     * Orders given keys by the rules of indexes and returns sorted keys.
     *
     * @param  array $keys
     * @param  array $indexes
     * @return array
     */
    public function orderKeys($keys, $indexes)
    {
        $orderedKeys = [];

        // Выполняет обход всех ключей исключая уже используемые ключи,
        // которые были задействованы во время обхода
        foreach ($keys as $key) {
            // Если ключ уже используется, то больше не ищем его
            if (! in_array($key, $orderedKeys)) {
                $nearbyKeys = $this->findNearbyKeys($key, $keys, $indexes);

                Arr::pushArray($orderedKeys, $nearbyKeys);
            }
        }

        return $orderedKeys;
    }

    /**
     * Finds nearby keys beside the basic key and returns them.
     *
     * @param  string $key
     * @param  array  $keys
     * @param  array  $indexes
     * @return array
     */
    public function findNearbyKeys($key, $keys, $indexes)
    {
        $nearbyKeys = [];

        // Deletes the key from indexes to exclude looping.
        $indexes = Arr::unset($indexes, $key);

        // Finds nearby keys on the left side.
        Arr::pushArray($nearbyKeys, $this->findNearbyKeysOnSomeSide($key, $keys, $indexes, self::LEFT_SIDE));

        // Sets the basic key in the middle or on the edge.
        $nearbyKeys[] = $key;

        // Finds nearby keys on the right side.
        Arr::pushArray($nearbyKeys, $this->findNearbyKeysOnSomeSide($key, $keys, $indexes, self::RIGHT_SIDE));

        return $nearbyKeys;
    }

    /**
     * Finds nearby keys of basic keys in the potential keys and given indexes.
     * Returns found keys.
     *
     * @param  array $keys
     * @param  array $potentialKeys
     * @param  array $indexes
     * @return mixed
     */
    public function findNearbyKeysOfBasicKeys($keys, $potentialKeys, $indexes)
    {
        $nearbyKeys = [];

        foreach ($keys as $innerKey) {
            Arr::pushArray($nearbyKeys, $this->findNearbyKeys($innerKey, $potentialKeys, $indexes));
        }

        return $nearbyKeys;
    }

    /**
     * Finds nearby keys beside the basic key on a given side: the left side
     * or the right side. Returns found keys.
     *
     * @param  string  $key
     * @param  array   $keys
     * @param  array   $indexes
     * @param  int     $side
     * @return array
     */
    public function findNearbyKeysOnSomeSide($key, $keys, $indexes, $side = self::LEFT_SIDE)
    {
        $nearbyKeys = [];

        if ($side == self::LEFT_SIDE) {
            $indexesOnSomeSide = $this->findIndexesOnLeft($indexes, $key);
        } elseif ($side == self::RIGHT_SIDE) {
            $indexesOnSomeSide = $this->findIndexesOnRight($indexes, $key);
        }

        if (isset($indexesOnSomeSide) && count($indexesOnSomeSide)) {
            $indexesOnSomeSide = $this->sortIndexes($indexesOnSomeSide, (self::LEFT_SIDE == 0));
            $keysOnSomeSide = array_keys($indexesOnSomeSide);

            Arr::pushArray($nearbyKeys, $this->findNearbyKeysOfBasicKeys($keysOnSomeSide, $keys, $indexes));
        }

        return $nearbyKeys;
    }

    /**
     * Finds indexes on the left side beside the key.
     *
     * @param  array  $indexes
     * @param  string $nearbyKey
     * @return array
     */
    public function findIndexesOnLeft($indexes, $nearbyKey)
    {
        return array_filter($indexes, function ($val) use ($nearbyKey) {
            if ($val['nearby_key'] == $nearbyKey && $val['direction'] < 0) {
                return $val;
            }
        });
    }

    /**
     * Finds indexes on the left side beside the key.
     *
     * @param  array  $indexes
     * @param  string $nearbyKey
     * @return array
     */
    public function findIndexesOnRight($indexes, $nearbyKey)
    {
        return array_filter($indexes, function ($val) use ($nearbyKey) {
            if ($val['nearby_key'] == $nearbyKey && $val['direction'] > 0) {
                return $val;
            }
        });
    }

    /**
     * Sorts indexes by ascending or descending and returns sorted indexes.
     *
     * @param  array  $indexes
     * @param  bool   $ascending  The sort order. If it is 'true',
     *                            then the ascending sort, else the descending sort.
     * @return array
     */
    public function sortIndexes($indexes, $ascending = true)
    {
        if (count($indexes) > 1) {
            uasort($indexes, function ($a, $b) use ($ascending) {
                if ($a['priority'] == $b['priority']) {
                    return 0;
                }

                return ($a['priority'] < $b['priority'] && $ascending) ? -1 : 1;
            });
        }

        return $indexes;
    }
}
