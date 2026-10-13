<?php

namespace Cardora\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCardoraInventory extends Migration
{
	protected $DBGroup = 'cardora';
	
    public function up()
    {
        /* Add fields for the table */
        $this->forge->addField([
            'carinv_id'          => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'dealer_id'          => [
                'type'   => 'VARCHAR',
                'constraint'     => '50',
            ],        
            'dealer_name'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'address'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'city'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],            
            'state'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],             
            'postalcode'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],            
            'phone'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],             
            'email'  => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ], 
            'vin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'stockid'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'year'       => [
                'type'           => 'INT',
                'constraint'     => '11',
            ],
            'make'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'model'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
            ],
            'engine'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'body'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'trim'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'transmission'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'odometer'       => [
                'type'           => 'INT',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'exterior_color'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'interior_color'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],           
            'price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
            ],
            'doors'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'fuel_type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],                      
            'is_used'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'default'         => 1,
            ],
            'is_certified'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'default'         => 0,
            ],            
            'number_of_passengers'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '10',
                'null'           => TRUE,
            ],
            'comments'       => [
                'type'           => 'blob',
                'null'           => TRUE,
            ],            
            'drivetrain'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],            
            'video_url'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
           'images'       => [
                'type'           => 'blob',
                'null'           => TRUE,
            ],       
        ]);

        /* Add the constraints for the table */
        $this->forge->addKey('carinv_id', TRUE);
        /* Create the table for the clients */
        /* Create the table for the clients */
        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];
        $this->forge->createTable('carinv', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('carinv');
    }
}
