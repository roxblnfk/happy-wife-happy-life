<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault15792db9b529b75b1c6c71b56c59c5f8 extends Migration
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
        ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
        ->addIndex(['chat_uuid'], ['name' => 'chat_message_index_chat_uuid_68769f18ca1a1', 'unique' => false])
        ->addForeignKey(['chat_uuid'], 'chat', ['uuid'], [
            'name' => 'chat_message_chat_uuid_fk',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->setPrimaryKeys(['uuid'])
        ->create();
        $this->table('chat_message_request')
        ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
        ->addColumn('message_uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
        ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
        ->addIndex(['message_uuid'], ['name' => 'chat_message_request_index_message_uuid_68769f18ccb18', 'unique' => false])
        ->addForeignKey(['message_uuid'], 'chat_message', ['uuid'], [
            'name' => 'chat_message_request_message_uuid_fk',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->setPrimaryKeys(['uuid'])
        ->create();
        $this->table('chat_message_request_chunk')
        ->addColumn('request_uuid', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
        ->addColumn('index', 'uuid', ['nullable' => false, 'defaultValue' => null, 'size' => 36])
        ->addColumn('content', 'text', ['nullable' => false, 'defaultValue' => null])
        ->addColumn('createdAt', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP'])
        ->addIndex(['request_uuid'], [
            'name' => 'chat_message_request_chunk_index_request_uuid_68769f18ccb51',
            'unique' => false,
        ])
        ->addForeignKey(['request_uuid'], 'chat_message_request', ['uuid'], [
            'name' => 'chat_message_request_chunk_request_uuid_fk',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
            'indexCreate' => true,
        ])
        ->setPrimaryKeys(['request_uuid', 'index'])
        ->create();
    }

    public function down(): void
    {
        $this->table('chat_message_request_chunk')->drop();
        $this->table('chat_message_request')->drop();
        $this->table('chat_message')->drop();
        $this->table('chat')->drop();
    }
}
