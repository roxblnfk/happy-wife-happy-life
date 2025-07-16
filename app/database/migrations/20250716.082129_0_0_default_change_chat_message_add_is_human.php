<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault72dfcc356b5f4e8ae7c63a3f0ad81631 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat_message')
            ->addColumn('is_human', 'boolean', ['nullable' => false, 'defaultValue' => true, 'size' => 1])
            ->update();
    }

    public function down(): void
    {
        $this->table('chat_message')
            ->dropColumn('is_human')
            ->update();
    }
}
