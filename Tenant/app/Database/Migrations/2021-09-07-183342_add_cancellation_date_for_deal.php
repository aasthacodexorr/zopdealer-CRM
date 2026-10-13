<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnCancellationDateForTheDeal extends Migration
{
    public function up()
    {
        $fields = [
            'cancellation_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ]
        ];

        $this->forge->addColumn('deals', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deals', [ 'cancellation_date']); // to drop one single column
    }
}
