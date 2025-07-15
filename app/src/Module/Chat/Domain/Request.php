<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\UuidInterface;

/**
 * AI chat entity.
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

    #[Column(type: 'uuid', nullable: false)]
    public UuidInterface $messageUuid;

    public \DateTimeImmutable $createdAt;

    /** @var list<Chunk> */
    #[HasMany(target: Chunk::class, innerKey: 'uuid', outerKey: 'requestUuid', orderBy: ['index' => 'ASC'])]
    public array $chunks = [];
}
