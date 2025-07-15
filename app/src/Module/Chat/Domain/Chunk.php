<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Ramsey\Uuid\UuidInterface;

/**
 * AI chat entity.
 */
#[EntityAttribute(
    table: 'chat_message_request_chunk',
)]
#[CreatedAt('createdAt')]
class Chunk extends ActiveRecord
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $requestUuid;

    #[Column(type: 'uuid', primary: true, nullable: false)]
    public int $index;

    #[Column(type: 'text', nullable: false)]
    public string $content;

    public \DateTimeImmutable $createdAt;
}
