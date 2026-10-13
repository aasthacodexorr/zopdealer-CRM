<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTestDrivesCustomersViewTable extends Migration
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
        $this->forge->createTable('test_drives_customers_view');

        // Insert default record
        $data = [
            'id' => 1,
            'header' => 'Test Drives Customers',
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
                    'column'=> 'driver_license',
                    'header'=> 'DRIVER LICENSE',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'license_expiry',
                    'header'=> 'LICENSE EXPIRY',
                    'component'=> 'date',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'street_address',
                    'header'=> 'STREET ADDRESS',
                    'component'=> 'text',
                    'visibility'=> '0'
                ],
                [
                    'column'=> 'apt',
                    'header'=> 'APT',
                    'component'=> 'text',
                    'visibility'=> '0'
                ],
                [
                    'column'=> 'city',
                    'header'=> 'CITY',
                    'component'=> 'text',
                    'visibility'=> '0'
                ],
                [
                    'column'=> 'province',
                    'header'=> 'PROVINCE',
                    'component'=> 'text',
                    'visibility'=> '1'
                ],
                [
                    'column'=> 'postal_code',
                    'header'=> 'POSTAL CODE',
                    'component'=> 'text',
                    'visibility'=> '0'
                ],
                [
                    'column'=> 'country',
                    'header'=> 'COUNTRY',
                    'component'=> 'text',
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

        $this->db->table('test_drives_customers_view')->insert($data);
    }

    public function down()
    {
        $this->forge->dropTable('test_drives_customers_view');
    }
}
