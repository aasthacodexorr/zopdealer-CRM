<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetTradeInFormValidation extends Migration
{

    public function up()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('lead_template_form_elements');
        $builder->set('dom_validation', 'required');
        $builder->whereIn('dom_name', ['model', 'make']);
        $builder->where('dom_validation', 'required|alpha_numeric_space');
        $builder->update();
    }

    public function down() {}
}
