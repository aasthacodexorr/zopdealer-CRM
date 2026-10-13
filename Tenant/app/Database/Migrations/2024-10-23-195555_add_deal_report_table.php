<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDealReportTable extends Migration
{
    public function up()
    {

        $this->forge->addField([
            'id' => [
                'type'   => 'INT',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'deal_id' => [
                'type'   => 'INT',
                'unsigned'       => FALSE,
            ],
            'type'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
                'default'        => ''
            ],
            'date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'retail_or_wholesale_flag'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => FALSE,
                'default'         => 0,
            ],
            'status'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'delivery_date_time'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'note' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'disclosure' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'condition' => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => '',
            ],
            'approved_by'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'salesperson'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => '',
            ],
            'close_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],

            'phonetic_string'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
            ],

            'created_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'updated_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'deleted_at'       => [
                'type'           => 'DATETIME',
                'null'           => TRUE,
            ],
            'brand'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => ''
            ],
            'certified'    => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
                'default'   => ''
            ],
            'cancellation_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],
            'search_keyword'          => [
                'type'   => 'TEXT',
                'null'   => TRUE,
                'default'   => ''
            ],
            'lock'       => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'unsigned'       => FALSE,
                'default'         => 0,
            ],
            'year'       => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => FALSE,
                'null'           => TRUE,
            ],
            'make'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'model'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'vin'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '20',
                'null'           => TRUE,
            ],
            'stock_no'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'odometer'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'           => TRUE,
            ],
            'exterior_color'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'interior_color'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'           => TRUE,
            ],
            'customer_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
                'default'       => '',
            ],
            'customer_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'customer_mobile'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'customer_email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'customer_company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'           => TRUE,
            ],
            'customer_apt'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'customer_street_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'customer_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'customer_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'customer_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'customer_country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],

            'vendor_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'           => TRUE,
            ],
            'vendor_phone'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'vendor_mobile'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '50',
                'null'   => TRUE,
            ],
            'vendor_email'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'vendor_company_name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],

            'vendor_apt'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'vendor_street_address'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null'   => TRUE,
            ],
            'vendor_city'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'vendor_province'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'vendor_postal_code'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],
            'vendor_country'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
                'null'   => TRUE,
            ],

            'vendor_purchase_date'       => [
                'type'           => 'DATE',
                'null'           => TRUE,
            ],

            'vendor_purchase_price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'vendor_purchase_discount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'vendor_purchase_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'vendor_purchase_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'vendor_purchase_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'additional_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_cost_discount'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_cost_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'selling_price'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'selling_price_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'selling_price_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'selling_price_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],


            'extended_warranty'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],


            'extended_warranty_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'extended_warranty_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'guarantee_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'guarantee_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'guarantee_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'guarantee_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'fees'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'fees_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'fees_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],


            'accessories'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],


            'accessories_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'accessories_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],


            'trade_value'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'net_difference'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'licence_fee'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'gasoline'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'lien_payout'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'lien_registration_fee'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'subtotal'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'payment_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'payment_details'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '500',
                'null'   =>         TRUE,
                'default'   =>      ''
            ],

            'insurance_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'insurance_total_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_cost_primary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_cost_secondary_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'insurance_cost_total_tax'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'total_balance_due'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'finance_commission_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'sale_commissions_total'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'additional_deal_revenue'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_deal_cost'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'additional_deal_cost_taxes_paid'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'vehicle_profit_loss'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'total_profit_loss'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

            'total_taxes_collected'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'total_taxes_paid'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],
            'total_taxes_difference'       => [
                'type'           => 'DECIMAL',
                'constraint'     => '8,2',
                'null'           => TRUE,
                'unsigned'       => FALSE,
                'default'       => 0,
            ],

        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addUniqueKey('deal_id');

        $attributes = [
            'ENGINE' => 'InnoDB',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'CHARACTER SET' => 'utf8mb4',
        ];

        $this->forge->createTable('deal_reports', FALSE, $attributes);
    }

    public function down()
    {
        $this->forge->dropTable('deal_reports');
    }
}
