<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttachmentColumnForTheEmailNotification extends Migration
{
    public function up()
    {
        $fields = [
            'attachment'       => [
                'type'           => 'TEXT',
                'default'       => NULL,
                'null'           => TRUE,
            ],

        ];

        $this->forge->addColumn('email_notifications', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('email_notifications', 'attachment');
    }
}
