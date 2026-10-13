<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSocialConnectTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'stock_no'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'facebook_status'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'instagram_status'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'google_status'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],                        
            'created_at'       => [
                'type'           => 'DATETIME',                
                'null'           => TRUE,
            ],
            'updated_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ]
        ]);

        $this->forge->addKey('id', TRUE);

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('social_connect', TRUE, $attributes);
    }

    public function down(){
        $this->forge->dropTable('social_connect');
    }
}