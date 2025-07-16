<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultAca81bc9b248eac1b5e9d30de0059c6b extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat_message_request')
        ->addColumn('status', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
        ->addColumn('output', 'json', ['nullable' => false, 'defaultValue' => ''])
        ->update();
    }

    public function down(): void
    {
        $this->table('chat_message_request')
        ->dropColumn('status')
        ->dropColumn('output')
        ->update();
    }
}
