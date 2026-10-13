<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestDrivesViewTable extends Migration
{
    public function up()
    {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'header' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'view' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'query' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'sort' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('test_drives_view');

        // Insert default record
        $data = [
            'id' => 1,
            'header' => 'Test Drives',
            'view' => json_encode([
                [
                    'column'=> 'first_name',
                    'header'=> 'FIRST NAME',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'last_name',
                    'header'=> 'LAST NAME',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'phone',
                    'header'=> 'PHONE',
                    'component'=> 'phone',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'email',
                    'header'=> 'EMAIL',
                    'component'=> 'email',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'vehicle',
                    'header'=> 'VEHICLE',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'stock_no',
                    'header'=> 'STOCK#',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'vin',
                    'header'=> 'VIN',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'number_plate',
                    'header'=> 'NUMBER PLATE',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'odo_start',
                    'header'=> 'ODO START',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'odo_end',
                    'header'=> 'ODO END',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'sales_rep_name',
                    'header'=> 'SALES REP',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'issue',
                    'header'=> 'ISSUES?',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'status',
                    'header'=> 'STATUS',
                    'component'=> 'badge',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'created_at',
                    'header'=> 'CREATION DATE',
                    'component'=> 'date',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'updated_at',
                    'header'=> 'MODIFIED DATE',
                    'component'=> 'date',
                    'visibility'=> '1'
                ],
            ]),
            'query' => json_encode([]),
            'sort' => json_encode(['created_at' => 'DESC']),
            'default' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('test_drives_view')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('test_drives_view');
    }
}
