<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeColumForTitleInWorkFlowTemplateStepActivity extends Migration
{
    public function up()
    {

        $fields = [
            'title' => [
                'type' => 'TEXT',
            ]
        ];
        $this->forge->modifyColumn('workflow_template_step_activity_action', $fields);
    }

    public function down()
    {
    }
}
