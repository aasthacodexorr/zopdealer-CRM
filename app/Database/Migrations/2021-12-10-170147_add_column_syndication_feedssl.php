<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnSyndicationFeedssl extends Migration
{
	protected $DBGroup = 'default';

    public function up()
    {
        $fields = [
            'ssl'       => [
                'type'           => 'INT',
                'constraint'     => '1',
                'default'        => 0,
            ],
        ];

        $this->forge->addColumn('syndication_feed', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('syndication_feed', 'ssl');
    }
}
