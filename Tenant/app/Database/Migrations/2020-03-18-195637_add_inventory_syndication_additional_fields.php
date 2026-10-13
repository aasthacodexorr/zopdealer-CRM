<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAditionalSyndicationFieldsCategories extends Migration
{
    public function up()
    {
        $fields = [
            'kijiji_category'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'autotrader_category'	=>[
				'type'           => 'VARCHAR',
				'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            'facebook_category'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'        => '',
            ],
            
        ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory', [ 'kijiji_category']); 
        $this->forge->dropColumn('inventory', [ 'autotrader_category']); 
        $this->forge->dropColumn('inventory', [ 'facebook_category']); 
    }
}
