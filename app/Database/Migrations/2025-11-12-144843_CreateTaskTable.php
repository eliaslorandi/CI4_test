<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaskTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_completed' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
             // Chave Estrangeira: Conecta a tarefa a uma pessoa
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'due_date' => [ //vencimento
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id'); // Índice para a chave estrangeira
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('category_id'); // Índice para a chave estrangeira
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['user_id', 'title']); // Evita tarefas com mesmo título para o mesmo usuário

        $this->forge->createTable('tasks');
    }

    public function down()
    {
        $this->forge->dropTable('tasks', true);
    }
}
