<?php

namespace Cardora\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdflead extends Migration
{
	protected $DBGroup = 'default';
	
    public function up()
    {
        /* Add fields for the table */
        $this->forge->addField([
            'id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'xsender'          => [
                'type'   => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],        
            'xto'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'xsubject'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'xdate'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],            
            'xdata'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ], 
            'message'       => [
                'type'           => 'TEXT',
                'null'           => TRUE,
            ],               
            'status'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'default'        => '0'
            ],            
        ]);

        /* Add the constraints for the table */
        $this->forge->addKey('id', TRUE);
        /* Create the table for the clients */
        /* Create the table for the clients */
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('adfdata', TRUE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('adfdata');
    }
}
