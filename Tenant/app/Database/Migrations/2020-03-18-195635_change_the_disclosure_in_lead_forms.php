<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeTheDisclosureFromVarcharToText extends Migration
{
    public function up()
    {
    
    $fields = [
            'disclosure' => [
                    'type' => 'TEXT',
            ],
    ];
    $this->forge->modifyColumn('lead_template_forms', $fields);

    }

    public function down()
    {
        $fields = [
            'disclosure' => [
                    'type' => 'VARCHAR',
            ],
    ];
   
   // $this->forge->modifyColumn('lead_template_forms', $fields);

    }

}