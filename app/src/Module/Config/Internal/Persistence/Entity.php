<?php

declare(strict_types=1);

namespace App\Module\Config\Internal\Persistence;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;

#[EntityAttribute(
    table: 'config',
)]
class Entity extends ActiveRecord
{
    #[Column(type: 'string', primary: true, nullable: false, size: 255)]
    public string $name;

    #[Column(type: 'text', nullable: false)]
    public string $value;
}
