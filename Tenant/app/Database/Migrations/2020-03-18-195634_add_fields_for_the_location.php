<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdditionalMetaDataForTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'dealer_registration_number'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '50',
                'null'           => TRUE,
                'default'        => '',
            ],
            'tax_number'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'rin_number'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'fax'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
                'default'        => '',
            ],
            'default'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => TRUE,
                'default'         => 0,
            ],
            
        ];

        $this->forge->addColumn('locations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('locations', [ 'dealer_registration_number']); 
        $this->forge->dropColumn('locations', [ 'tax_number']); 
        $this->forge->dropColumn('locations', [ 'rin_number']); 
        $this->forge->dropColumn('locations', [ 'default']); 
        $this->forge->dropColumn('locations', [ 'fax']);// to drop one single column
    }
}
