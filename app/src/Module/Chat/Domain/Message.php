<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\LLM\Internal\Domain\Request;
use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * AI chat entity.
 *
 * @final
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

    #[Column(type: 'string', nullable: true)]
    public ?string $message = null;

    #[Column(type: 'string', nullable: false, default: 'user', typecast: MessageRole::class, size: 32)]
    public MessageRole $role;

    public \DateTimeImmutable $createdAt;

    #[Column(type: 'uuid', nullable: true, typecast: 'uuid')]
    public UuidInterface|null $requestUuid = null;

    #[BelongsTo(target: Request::class, innerKey: 'requestUuid', outerKey: 'uuid', cascade: false, nullable: true, fkOnDelete: 'SET NULL')]
    private ?Request $request = null;

    public static function create(Chat|UuidInterface $chat, ?string $message, MessageRole $role): self
    {
        return self::make([
            'uuid' => Uuid::uuid7(),
            'chatUuid' => $chat instanceof Chat ? $chat->uuid : $chat,
            'message' => $message,
            'status' => MessageStatus::Pending,
            'role' => $role,
            'createdAt' => new \DateTimeImmutable(),
        ]);
    }
}
