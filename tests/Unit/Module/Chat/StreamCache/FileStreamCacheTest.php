<?php

declare(strict_types=1);

namespace Tests\Unit\Module\Chat\StreamCache;

use App\Module\Chat\Internal\StreamCache;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(StreamCache\FileCache::class)]
final class FileStreamCacheTest extends StreamCacheTestAbstract
{
    protected function createStreamCache(): StreamCache
    {
        return new StreamCache\FileCache('runtime/tests/stream-cache');
    }
}
