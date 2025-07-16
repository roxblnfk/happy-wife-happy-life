<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Request to LLM.
 */
#[EntityAttribute(
    table: 'chat_message_request',
)]
#[Uuid7('uuid')]
#[CreatedAt('createdAt')]
class Request extends ActiveRecord
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $uuid;

    /**
     * @var non-empty-string The model to use for the request.
     */
    #[Column(type: 'string', nullable: false)]
    public string $model;

    #[Column(type: 'json', nullable: false, default: [], typecast: 'json', castDefault: true)]
    public array $options = [];

    #[Column(type: 'json', nullable: false, default: '', typecast: 'json', castDefault: true)]
    public array|string $input;

    public \DateTimeImmutable $createdAt;

    /**
     * Creates a new LLM request.
     *
     * @param non-empty-string $model The model to use for the request.
     * @param array|string|object $input The input data for the request.
     * @param array $options Additional options for the request.
     * @return self
     */
    public static function create(
        string $model,
        array|string|object $input,
        array $options = [],
    ): self {
        return static::make([
            'uuid' => Uuid::uuid7(),
            'model' => $model,
            'input' => $input,
            'options' => $options,
            'createdAt' => new \DateTimeImmutable(),
        ]);
    }
}
