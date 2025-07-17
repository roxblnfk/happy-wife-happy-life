<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault190ab09b530acc0eb41838fc3d2b1f9d extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat_message')
            ->addColumn('role', 'string', ['nullable' => false, 'defaultValue' => 'user', 'size' => 32])
            ->dropColumn('is_human')
            ->update();
    }

    public function down(): void
    {
        $this->table('chat_message')
            ->addColumn('is_human', 'boolean', ['nullable' => false, 'defaultValue' => 'TRUE', 'size' => 1])
            ->dropColumn('role')
            ->update();
    }
}
