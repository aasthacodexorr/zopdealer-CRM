<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeColumForSubjectInWorkFlowTemplateStepEmail extends Migration
{
    public function up()
    {

        $fields = [
            'subject' => [
                'type' => 'TEXT',
            ]
        ];
        $this->forge->modifyColumn('workflow_template_step_email_action', $fields);
    }

    public function down()
    {
    }
}
