<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReviewFeedbackViewToTheUser extends Migration
{
    public function up()
    {
        $fields = [
            'review_feedback_view'	=>[
				'type'           => 'INT',
				'default'		 => 1
			],
        ];

        $this->forge->addColumn('user_metadata', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_metadata', [ 'review_feedback_view']); // to drop one single column
    }
}
