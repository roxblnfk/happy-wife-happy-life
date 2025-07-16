<?php

declare(strict_types=1);

namespace Tests\Unit\Module\StreamCache;

use App\Module\LLM\Internal\StreamCache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(StreamCache::class)]
abstract class StreamCacheTestAbstract extends TestCase
{
    protected StreamCache $streamCache;

    public static function provideVariousDataTypes(): \Generator
    {
        yield 'empty string' => [''];
        yield 'single character' => ['a'];
        yield 'unicode characters' => ['Héllö, Wörld! 🌍'];
        yield 'json data' => ['{"key": "value", "number": 42}'];
        yield 'multiline text' => ["Line 1\nLine 2\nLine 3"];
        yield 'special characters' => ['!@#$%^&*()_+-=[]{}|;:,.<>?'];
        yield 'large text' => [\str_repeat('A', 10000)];
    }

    public static function provideReadParameters(): \Generator
    {
        $data = 'Hello, World!';

        yield 'default parameters' => [$data, 0, 1024, $data];
        yield 'zero offset, small length' => [$data, 0, 5, 'Hello'];
        yield 'middle offset, default length' => [$data, 7, 1024, 'World!'];
        yield 'middle offset, exact length' => [$data, 7, 5, 'World'];
        yield 'large offset, default length' => [$data, 100, 1024, ''];
        yield 'zero offset, large length' => [$data, 0, 10000, $data];
        yield 'end of string offset' => [$data, 13, 1024, ''];
        yield 'one character read' => [$data, 0, 1, 'H'];
    }

    public function testWriteCreatesNewStreamWithData(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello, World!';

        // Act
        $result = $this->streamCache->write($streamId, $data);

        // Assert
        self::assertSame($data, $result);
        self::assertTrue($this->streamCache->exists($streamId));
        self::assertSame($data, $this->streamCache->read($streamId));
    }

    public function testWriteWithAppendFalseOverwritesExistingData(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $initialData = 'Initial data';
        $newData = 'New data';

        // Act
        $this->streamCache->write($streamId, $initialData);
        $result = $this->streamCache->write($streamId, $newData, false);

        // Assert
        self::assertSame($newData, $result);
        self::assertSame($newData, $this->streamCache->read($streamId));
    }

    public function testWriteWithAppendTrueAppendsToExistingData(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $initialData = 'Initial data';
        $appendData = ' appended';
        $expectedData = $initialData . $appendData;

        // Act
        $this->streamCache->write($streamId, $initialData);
        $result = $this->streamCache->write($streamId, $appendData, true);

        // Assert
        self::assertSame($expectedData, $result);
        self::assertSame($expectedData, $this->streamCache->read($streamId));
    }

    public function testReadReturnsDataFromSpecificOffset(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello, World!';
        $offset = 7;
        $expectedData = 'World!';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId, $offset);

        // Assert
        self::assertSame($expectedData, $result);
    }

    public function testReadWithSpecificLengthReturnsLimitedData(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello, World!';
        $offset = 0;
        $length = 5;
        $expectedData = 'Hello';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId, $offset, $length);

        // Assert
        self::assertSame($expectedData, $result);
    }

    public function testReadWithOffsetAndLengthReturnsCorrectSubstring(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello, World!';
        $offset = 7;
        $length = 5;
        $expectedData = 'World';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId, $offset, $length);

        // Assert
        self::assertSame($expectedData, $result);
    }

    public function testReadWithOffsetBeyondDataSizeReturnsEmptyString(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello';
        $offset = 100;

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId, $offset);

        // Assert
        self::assertSame('', $result);
    }

    public function testReadFromNonExistentStreamReturnsEmptyString(): void
    {
        // Arrange
        $streamId = 'non-existent-stream';

        // Act
        $result = $this->streamCache->read($streamId);

        // Assert
        self::assertSame('', $result);
    }

    public function testExistsReturnsTrueForExistingStream(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Test data';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->exists($streamId);

        // Assert
        self::assertTrue($result);
    }

    public function testExistsReturnsFalseForNonExistentStream(): void
    {
        // Arrange
        $streamId = 'non-existent-stream';

        // Act
        $result = $this->streamCache->exists($streamId);

        // Assert
        self::assertFalse($result);
    }

    public function testDeleteRemovesExistingStream(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Test data';

        // Act
        $this->streamCache->write($streamId, $data);
        $this->streamCache->delete($streamId);

        // Assert
        self::assertFalse($this->streamCache->exists($streamId));
        self::assertSame('', $this->streamCache->read($streamId));
    }

    public function testDeleteNonExistentStreamDoesNotThrow(): void
    {
        // Arrange
        $streamId = 'non-existent-stream';

        // Act & Assert (no exception should be thrown)
        $this->streamCache->delete($streamId);
        self::assertFalse($this->streamCache->exists($streamId));
    }

    public function testSizeReturnsCorrectDataSize(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $data = 'Hello, World!';
        $expectedSize = \strlen($data);

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->size($streamId);

        // Assert
        self::assertSame($expectedSize, $result);
    }

    public function testSizeReturnsZeroForNonExistentStream(): void
    {
        // Arrange
        $streamId = 'non-existent-stream';

        // Act
        $result = $this->streamCache->size($streamId);

        // Assert
        self::assertSame(0, $result);
    }

    public function testSizeUpdatesAfterAppend(): void
    {
        // Arrange
        $streamId = 'test-stream-id';
        $initialData = 'Hello';
        $appendData = ', World!';
        $expectedSize = \strlen($initialData . $appendData);

        // Act
        $this->streamCache->write($streamId, $initialData);
        $this->streamCache->write($streamId, $appendData, true);
        $result = $this->streamCache->size($streamId);

        // Assert
        self::assertSame($expectedSize, $result);
    }

    public function testClearRemovesAllStreams(): void
    {
        // Arrange
        $streamId1 = 'stream-1';
        $streamId2 = 'stream-2';
        $data1 = 'Data 1';
        $data2 = 'Data 2';

        // Act
        $this->streamCache->write($streamId1, $data1);
        $this->streamCache->write($streamId2, $data2);
        $this->streamCache->clear();

        // Assert
        self::assertFalse($this->streamCache->exists($streamId1));
        self::assertFalse($this->streamCache->exists($streamId2));
        self::assertSame('', $this->streamCache->read($streamId1));
        self::assertSame('', $this->streamCache->read($streamId2));
    }

    public function testClearOnEmptyCacheDoesNotThrow(): void
    {
        // Act & Assert (no exception should be thrown)
        $this->streamCache->clear();
        self::assertTrue(true); // Test passes if no exception is thrown
    }

    #[DataProvider('provideVariousDataTypes')]
    public function testWriteAndReadWithVariousDataTypes(string $data): void
    {
        // Arrange
        $streamId = 'test-stream-id';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId);

        // Assert
        self::assertSame($data, $result);
    }

    #[DataProvider('provideReadParameters')]
    public function testReadWithVariousParameters(string $data, int $offset, int $length, string $expected): void
    {
        // Arrange
        $streamId = 'test-stream-id';

        // Act
        $this->streamCache->write($streamId, $data);
        $result = $this->streamCache->read($streamId, $offset, $length);

        // Assert
        self::assertSame($expected, $result);
    }

    public function testMultipleStreamsAreIndependent(): void
    {
        // Arrange
        $streamId1 = 'stream-1';
        $streamId2 = 'stream-2';
        $data1 = 'Data for stream 1';
        $data2 = 'Data for stream 2';

        // Act
        $this->streamCache->write($streamId1, $data1);
        $this->streamCache->write($streamId2, $data2);

        // Assert
        self::assertSame($data1, $this->streamCache->read($streamId1));
        self::assertSame($data2, $this->streamCache->read($streamId2));
        self::assertSame(\strlen($data1), $this->streamCache->size($streamId1));
        self::assertSame(\strlen($data2), $this->streamCache->size($streamId2));
    }

    public function testStreamModificationsAreIsolated(): void
    {
        // Arrange
        $streamId1 = 'stream-1';
        $streamId2 = 'stream-2';
        $data1 = 'Original data 1';
        $data2 = 'Original data 2';
        $newData1 = 'Modified data 1';

        // Act
        $this->streamCache->write($streamId1, $data1);
        $this->streamCache->write($streamId2, $data2);
        $this->streamCache->write($streamId1, $newData1);

        // Assert
        self::assertSame($newData1, $this->streamCache->read($streamId1));
        self::assertSame($data2, $this->streamCache->read($streamId2));
    }

    public function testStreamDeletionDoesNotAffectOtherStreams(): void
    {
        // Arrange
        $streamId1 = 'stream-1';
        $streamId2 = 'stream-2';
        $data1 = 'Data 1';
        $data2 = 'Data 2';

        // Act
        $this->streamCache->write($streamId1, $data1);
        $this->streamCache->write($streamId2, $data2);
        $this->streamCache->delete($streamId1);

        // Assert
        self::assertFalse($this->streamCache->exists($streamId1));
        self::assertTrue($this->streamCache->exists($streamId2));
        self::assertSame($data2, $this->streamCache->read($streamId2));
    }

    protected function setUp(): void
    {
        // Arrange (common setup)
        $this->streamCache = $this->createStreamCache();
    }

    protected function tearDown(): void
    {
        // Clean up test environment
        $this->streamCache->clear();
        parent::tearDown();
    }

    abstract protected function createStreamCache(): StreamCache;
}
