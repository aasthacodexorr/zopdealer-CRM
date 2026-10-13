<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInventoryColumnsAndRemoveFeatureColumn extends Migration
{
    public function up()
    {
    
        $this->forge->dropColumn('inventory','features');
        $fields = [
                'standard' => [
                    'type'   => 'TEXT',
                    'null'   => TRUE,
                ],
                'optional' => [
                        'type'   => 'TEXT',
                        'null'   => TRUE,
                    ],
                'technical_specification' => [
                    'type'   => 'TEXT',
                    'null'   => TRUE,
                ],
                'generic_equipment'          => [
                        'type'   => 'TEXT',
                        'null'   => TRUE,
                    ],
                'vin_company' => [
                        'type'           => 'TINYINT',
                        'constraint'     => '4',
                        'unsigned'       => TRUE,
                        'null'   => TRUE,
                ],
    
                ];
    
            $this->forge->addColumn('inventory', $fields);


    }

    public function down()
    {
        $fields = [
                'features'          => [
                    'type'   => 'blob',
                    'null'   => TRUE,
                ]
    
                ];
    
            $this->forge->addColumn('inventory', $fields);
            $this->forge->dropColumn('inventory','standard');
            $this->forge->dropColumn('inventory','optional');
            $this->forge->dropColumn('inventory','technical_specification');
            $this->forge->dropColumn('inventory','generic_equipment');
            $this->forge->dropColumn('inventory','vin_company');



    }

}