<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneticSearchField extends Migration
{
    public function up()
    {
        $fields = [
            'phonetic_string'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ]

            ];

        $this->forge->addColumn('inventory', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory','phonetic_string');
    }
}
