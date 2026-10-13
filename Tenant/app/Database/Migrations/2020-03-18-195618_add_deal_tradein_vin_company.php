<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealColumnsAndRemoveFeatureColumn extends Migration
{
    public function up()
    {
    
        $this->forge->dropColumn('deal_trade_in','features');
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
    
            $this->forge->addColumn('deal_trade_in', $fields);


    }

    public function down()
    {
        $fields = [
                'features'          => [
                    'type'   => 'blob',
                    'null'   => TRUE,
                ]
    
                ];
    
            $this->forge->addColumn('deal_trade_in', $fields);
            $this->forge->dropColumn('deal_trade_in','standard');
            $this->forge->dropColumn('deal_trade_in','optional');
            $this->forge->dropColumn('deal_trade_in','technical_specification');
            $this->forge->dropColumn('deal_trade_in','generic_equipment');
            $this->forge->dropColumn('deal_trade_in','vin_company');



    }

}