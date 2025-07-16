<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal\StreamCache;

use App\Module\LLM\Internal\StreamCache;

/**
 * File-based implementation of StreamCache for persistent storage.
 */
final class FileCache implements StreamCache
{
    private const FILE_MODE = 0644;
    private const DIR_MODE = 0755;

    public function __construct(
        private readonly string $cacheDirectory,
    ) {
        $this->ensureDirectoryExists();
    }

    public function read(string $id, int $offset = 0, int $length = 10_240): string
    {
        if (!$this->exists($id)) {
            return '';
        }

        $filePath = $this->getFilePath($id);
        $fileSize = \filesize($filePath);

        if ($fileSize === false || $offset >= $fileSize) {
            return '';
        }

        $handle = \fopen($filePath, 'rb');

        if ($handle === false) {
            return '';
        }

        try {
            if ($offset > 0 && \fseek($handle, $offset) !== 0) {
                return '';
            }

            $data = \fread($handle, $length);

            return $data === false ? '' : $data;
        } finally {
            \fclose($handle);
        }
    }

    public function write(string $id, string $data, bool $append = false): string
    {
        $this->ensureDirectoryExists();

        $filePath = $this->getFilePath($id);
        $mode = $append ? 'ab' : 'wb';

        $handle = \fopen($filePath, $mode);

        if ($handle === false) {
            throw new \RuntimeException("Unable to open file for writing: {$filePath}");
        }

        try {
            if (\fwrite($handle, $data) === false) {
                throw new \RuntimeException("Unable to write data to file: {$filePath}");
            }
        } finally {
            \fclose($handle);
        }

        \chmod($filePath, self::FILE_MODE);

        return $this->readEntireFile($filePath);
    }

    public function exists(string $id): bool
    {
        return \file_exists($this->getFilePath($id));
    }

    public function delete(string $id): void
    {
        $filePath = $this->getFilePath($id);

        if (\file_exists($filePath)) {
            \unlink($filePath);
        }
    }

    public function size(string $id): int
    {
        if (!$this->exists($id)) {
            return 0;
        }

        $size = \filesize($this->getFilePath($id));

        return $size === false ? 0 : $size;
    }

    public function clear(): void
    {
        if (!\is_dir($this->cacheDirectory)) {
            return;
        }

        $iterator = new \DirectoryIterator($this->cacheDirectory);

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isFile() && $this->isStreamCacheFile($fileInfo->getFilename())) {
                \unlink($fileInfo->getPathname());
            }
        }
    }

    private function getFilePath(string $id): string
    {
        $safeId = $this->sanitizeId($id);

        return $this->cacheDirectory . \DIRECTORY_SEPARATOR . $safeId . '.cache';
    }

    private function sanitizeId(string $id): string
    {
        // Replace unsafe characters with underscores and ensure filename safety
        return \preg_replace('/[^a-zA-Z0-9\-_]/', '_', $id);
    }

    private function isStreamCacheFile(string $filename): bool
    {
        return \str_ends_with($filename, '.cache');
    }

    private function ensureDirectoryExists(): void
    {
        if (!\is_dir($this->cacheDirectory)) {
            if (!\mkdir($this->cacheDirectory, self::DIR_MODE, true) && !\is_dir($this->cacheDirectory)) {
                throw new \RuntimeException("Unable to create cache directory: {$this->cacheDirectory}");
            }
        }
    }

    private function readEntireFile(string $filePath): string
    {
        $content = \file_get_contents($filePath);

        return $content === false ? '' : $content;
    }
}
