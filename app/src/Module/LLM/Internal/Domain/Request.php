<?php

declare(strict_types=1);

namespace App\Module\LLM\Internal\Domain;

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
    table: 'chat_message_request',
)]
#[Uuid7('uuid')]
#[CreatedAt('createdAt')]
class Request extends ActiveRecord
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $uuid;

    public \DateTimeImmutable $createdAt;
}
