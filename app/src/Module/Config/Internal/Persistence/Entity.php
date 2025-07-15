<?php

declare(strict_types=1);

namespace App\Module\Config\Internal\Persistence;

use App\Module\ORM\ActiveRecord;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity as EntityAttribute;

#[EntityAttribute(
    table: 'settings',
)]
class Entity extends ActiveRecord
{
    #[Column(type: 'string', primary: true, nullable: false, size: 255)]
    public string $name;

    #[Column(type: 'text', nullable: false)]
    public string $value;

    public function __construct(string $name = '', string $value = '')
    {
        $this->name = $name;
        $this->value = $value;
    }
}
