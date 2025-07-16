<?php

declare(strict_types=1);

namespace App\Module\Chat\Internal\StreamCache;

use App\Module\Chat\Internal\StreamCache;

/**
 * Array-based implementation of StreamCache for in-memory storage.
 */
final class ArrayCache implements StreamCache
{
    /**
     * @var array<non-empty-string, string>
     */
    private array $cache = [];

    public function read(string $id, int $offset = 0, int $length = \PHP_INT_MAX): string
    {
        if (!$this->exists($id)) {
            return '';
        }

        $data = $this->cache[$id];
        $dataLength = \strlen($data);

        return $offset >= $dataLength
            ? ''
            : \substr($data, $offset, $length);
    }

    public function write(string $id, string $data, bool $append = false): string
    {
        $append && $this->exists($id)
            ? $this->cache[$id] .= $data
            : $this->cache[$id] = $data;

        return $this->cache[$id];
    }

    public function exists(string $id): bool
    {
        return \array_key_exists($id, $this->cache);
    }

    public function delete(string $id): void
    {
        unset($this->cache[$id]);
    }

    public function size(string $id): int
    {
        if (!$this->exists($id)) {
            return 0;
        }

        return \strlen($this->cache[$id]);
    }

    public function clear(): void
    {
        $this->cache = [];
    }
}
