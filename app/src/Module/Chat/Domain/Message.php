<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * AI chat entity.
 */
#[EntityAttribute(
    table: 'chat_message',
)]
#[Uuid7('uuid')]
#[CreatedAt('createdAt')]
class Message extends ActiveRecord
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $uuid;

    #[Column(type: 'uuid', nullable: false, typecast: 'uuid')]
    public UuidInterface $chatUuid;

    #[Column(type: 'string', typecast: MessageStatus::class)]
    public MessageStatus $status = MessageStatus::Completed;

    #[Column(type: 'string')]
    public ?string $message = null;

    #[Column(type: 'boolean', nullable: false, default: true, typecast: 'bool')]
    public bool $isHuman = true;

    public \DateTimeImmutable $createdAt;

    #[Column(type: 'uuid', nullable: true, typecast: 'uuid')]
    public UuidInterface|null $requestUuid = null;

    public static function create(Chat|UuidInterface $chat, ?string $message): self
    {
        return self::make([
            'uuid' => Uuid::uuid7(),
            'chatUuid' => $chat instanceof Chat ? $chat->uuid : $chat,
            'message' => $message,
            'status' => MessageStatus::Pending,
            'createdAt' => new \DateTimeImmutable(),
        ]);
    }
}
