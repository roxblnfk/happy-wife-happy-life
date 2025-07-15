<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\Annotated\Annotation\Relation\HasOne;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
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

    #[Column(type: 'uuid', nullable: false)]
    public UuidInterface $chatUuid;

    #[Column(type: 'string', typecast: MessageStatus::class)]
    public MessageStatus $status = MessageStatus::Completed;

    public \DateTimeImmutable $createdAt;

    #[HasOne(target: Request::class, innerKey: 'uuid', outerKey: 'messageUuid', nullable: true)]
    public ?Request $request = null;
}
