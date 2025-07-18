<?php

declare(strict_types=1);

namespace App\Module\Chat\Domain;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\ORM\Entity\Behavior\CreatedAt;
use Cycle\ORM\Entity\Behavior\Uuid\Uuid7;
use Cycle\ORM\Parser\Typecast;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * AI chat entity.
 */
#[EntityAttribute(
    table: 'chat',
    typecast: [
        Typecast::class,
    ],
)]
#[Uuid7('uuid')]
#[CreatedAt('createdAt')]
class Chat extends ActiveRecord implements \JsonSerializable
{
    #[Column(type: 'uuid', primary: true, nullable: false, typecast: 'uuid')]
    public UuidInterface $uuid;

    #[Column(type: 'string', nullable: true)]
    public ?string $title = null;

    // #[Column(type: 'string')]
    // public string $agentType;

    // #[Column(type: 'string')]
    // public string $status;

    public \DateTimeImmutable $createdAt;

    /** @var list<Message> */
    #[HasMany(target: Message::class, innerKey: 'uuid', outerKey: 'chatUuid', orderBy: ['createdAt' => 'ASC'])]
    public array $messages = [];

    public static function create(?string $title = null): self
    {
        return static::make([
            'uuid' => Uuid::uuid7(),
            'title' => $title,
            'createdAt' => new \DateTimeImmutable(),
        ]);
    }

    /**
     * @return array{
     *     id: string,
     *     title: string|null,
     *     created_at: string
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->uuid->toString(),
            'title' => $this->title,
            'created_at' => $this->createdAt->format(\DateTimeInterface::ATOM),
        ];
    }
}
