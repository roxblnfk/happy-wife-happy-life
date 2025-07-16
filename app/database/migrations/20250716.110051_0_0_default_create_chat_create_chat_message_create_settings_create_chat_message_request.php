<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultFf8f9396df28735a1addfb23ed031e73 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('chat')
            ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
            ->addColumn('title', 'string', ['nullable' => true, 'defaultValue' => null, 'size' => 255])
            ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
            ->setPrimaryKeys(['uuid'])
            ->create();
        $this->table('chat_message')
            ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
            ->addColumn('chat_uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
            ->addColumn('status', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('message', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('is_human', 'boolean', ['nullable' => false, 'defaultValue' => true, 'size' => 1])
            ->addColumn('request_uuid', 'uuid', ['nullable' => true, 'defaultValue' => null, 'size' => 36])
            ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['chat_uuid'], ['name' => 'chat_message_index_chat_uuid_68778663c6624', 'unique' => false])
            ->addForeignKey(['chat_uuid'], 'chat', ['uuid'], [
                'name' => 'chat_message_chat_uuid_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ])
            ->setPrimaryKeys(['uuid'])
            ->create();
        $this->table('config')
            ->addColumn('name', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('value', 'text', ['nullable' => false, 'defaultValue' => null])
            ->setPrimaryKeys(['name'])
            ->create();
        $this->table('chat_message_request')
            ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
            ->addColumn('model', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 255])
            ->addColumn('options', 'json', ['nullable' => false, 'defaultValue' => '[]'])
            ->addColumn('input', 'json', ['nullable' => false, 'defaultValue' => ''])
            ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
            ->setPrimaryKeys(['uuid'])
            ->create();
    }

    public function down(): void
    {
        $this->table('chat_message_request')->drop();
        $this->table('config')->drop();
        $this->table('chat_message')->drop();
        $this->table('chat')->drop();
    }
}
