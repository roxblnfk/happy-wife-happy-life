<?php

declare(strict_types=1);

namespace App\Module\Chat\Internal;

/**
 * Cache interface for streaming data.
 */
interface StreamCache
{
    /**
     * Read a portion of the cached stream data.
     *
     * @param non-empty-string $id The unique identifier for the cached stream.
     * @param int<0, max> $offset The starting position in the stream to read from.
     * @param int<1, max> $length The maximum number of bytes to read from the stream.
     */
    public function read(string $id, int $offset = 0, int $length = PHP_INT_MAX): string;

    /**
     * Write data to the cached stream.
     *
     * @param non-empty-string $id The unique identifier for the cached stream.
     * @param non-empty-string $data The data to write to the stream.
     */
    public function write(string $id, string $data, bool $append = false): string;

    /**
     * Check if the cached stream exists.
     *
     * @param non-empty-string $id The unique identifier for the cached stream.
     */
    public function exists(string $id): bool;

    /**
     * Delete the cached stream.
     *
     * @param non-empty-string $id The unique identifier for the cached stream.
     */
    public function delete(string $id): void;

    /**
     * Get the size of the cached stream.
     *
     * @param non-empty-string $id The unique identifier for the cached stream.
     * @return int<0, max>
     */
    public function size(string $id): int;

    /**
     * Clear all cached streams.
     */
    public function clear(): void;
}
