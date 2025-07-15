<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault7304df858c72086f8742c69d2fe29080 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('settings')
            ->addColumn('name', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('value', 'text', ['nullable' => false, 'defaultValue' => null])
            ->setPrimaryKeys(['name'])
            ->create();
    }

    public function down(): void
    {
        $this->table('settings')->drop();
    }
}
