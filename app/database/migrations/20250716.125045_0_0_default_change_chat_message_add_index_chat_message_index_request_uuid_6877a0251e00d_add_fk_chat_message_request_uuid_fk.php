<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault5e28ec86aab907248e4225bbb01cd9cf extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat_message')
            ->addIndex(['request_uuid'], ['name' => 'chat_message_index_request_uuid_6877a0251e00d', 'unique' => false])
            ->addForeignKey(['request_uuid'], 'chat_message_request', ['uuid'], [
                'name' => 'chat_message_request_uuid_fk',
                'delete' => 'SET NULL',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('chat_message')
            ->dropForeignKey(['request_uuid'])
            ->dropIndex(['request_uuid'])
            ->update();
    }
}
