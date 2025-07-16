<?php

declare(strict_types=1);

namespace Tests\Unit\Module\Chat\StreamCache;

use App\Module\Chat\Internal\StreamCache;
use App\Module\Chat\Internal\StreamCache\ArrayCache;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ArrayCache::class)]
final class ArrayStreamCacheTest extends StreamCacheTestAbstract
{
    protected function createStreamCache(): StreamCache
    {
        return new ArrayCache();
    }
}
