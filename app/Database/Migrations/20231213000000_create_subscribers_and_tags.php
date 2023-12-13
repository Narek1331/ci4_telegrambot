<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscribersAndTags extends Migration
{
    public function up()
    {
        // Create subscribers table
        $this->forge->addField([
            'id'    => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'name'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('subscribers');

        // Create tags table
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'subscriber_id'  => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'tag_name'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'value'          => ['type' => 'VARCHAR', 'constraint' => 255], // Assuming 'value' is the column for tag values
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('subscriber_id', 'subscribers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tags');
    }

    public function down()
    {
        $this->forge->dropTable('tags');
        $this->forge->dropTable('subscribers');
    }
}
