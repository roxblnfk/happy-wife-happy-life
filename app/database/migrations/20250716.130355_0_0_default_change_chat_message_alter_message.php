<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultE70cf9b4ca6affeecdd91b8a4a856510 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat_message')
        ->alterColumn('message', 'string', ['nullable' => true, 'defaultValue' => null, 'size' => 255])
        ->update();
    }

    public function down(): void
    {
        $this->table('chat_message')
        ->alterColumn('message', 'text', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
        ->update();
    }
}
