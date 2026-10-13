<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use DateTime;

class AddWebsiteTypeForTheWordpressJob extends Migration
{
    public function up()
    {
        $fields = [
            'website_type'          => [
                'type'   => 'INT',
                'default'   => NULL
            ],
        ];

        $this->forge->addColumn('wordpress_jobs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('wordpress_jobs', ['website_type']); // to drop one single column
    }
}
