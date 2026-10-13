<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssigneeForCustomer extends Migration
{
    public function up()
    {
        $fields = [
            'assignee'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '350',
                'null'   => TRUE,
            ],
        ];

        $this->forge->addColumn('customers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('customers', [ 'assignee']); // to drop one single column
    }
}
