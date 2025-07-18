<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault00acb53e31127723a2f0b2a95ff38a23 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('calendar_event')
            ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
            ->addColumn('title', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('date', 'date', ['nullable' => false, 'defaultValue' => null])
            ->addColumn('period', 'string', ['nullable' => true, 'defaultValue' => null, 'size' => 255])
            ->addColumn('description', 'text', ['nullable' => true, 'defaultValue' => null])
            ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updatedAt', 'datetime', ['nullable' => true, 'defaultValue' => null])
            ->setPrimaryKeys(['uuid'])
            ->create();
    }

    public function down(): void
    {
        $this->table('calendar_event')->drop();
    }
}
