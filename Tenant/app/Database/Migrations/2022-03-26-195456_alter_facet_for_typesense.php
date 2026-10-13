<?php

namespace Tenant\app\Database\Migrations;

use CodeIgniter\Database\Migration;
use Tenant\app\Libraries\IndexService\Typesense;

class AlterationOfTypesenseMigrationForLitreIndex extends Migration
{
    public function up()
    {
        $typesense_instance = new Typesense();
        $typesense_instance->setCollection(md5(db_connect()->database));
        $typesense_instance->delete();

        $collection_data = '{
            "name" : "' . md5(db_connect()->database) . '",
            "fields" : [
                {"name": "inventory_id", "type": "int64" , "index": true, "facet": false},
                {"name": "vin", "type": "string" , "index": true, "facet": false},
                {"name": "stock_no", "type": "string" , "index": true, "facet": false},
                {"name": "status", "type": "string" , "index": true, "facet": true},
                {"name": "sub_status", "type": "string" , "index": true, "facet": true},
                {"name": "feature_listing", "type": "int32" , "index": true, "facet": true},
                {"name": "vehicle_type", "type": "string" , "index": true, "facet": true},
                {"name": "visibility", "type": "int32" , "index": true, "facet": false},
                {"name": "vehicle_tags", "type": "string[]" , "index": true, "facet": true},
                {"name": "year", "type": "int32" , "index": true, "facet": true},
                {"name": "make", "type": "string" , "index": true, "facet": true},
                {"name": "model", "type": "string" , "index": true, "facet": true},
                {"name": "trim", "type": "string" , "index": true, "facet": false},
                {"name": "body_type", "type": "string" , "index": true, "facet": true},
                {"name": "door", "type": "string" , "index": true, "facet": true},
                {"name": "engine", "type": "string" , "index": true, "facet": true},
                {"name": "litres", "type": "string" , "index": true, "facet": true},
                {"name": "transmission", "type": "string" , "index": true, "facet": true},
                {"name": "drivetrain", "type": "string" , "index": true, "facet": true},
                {"name": "passengers", "type": "string" , "index": true, "facet": true},
                {"name": "odometer", "type": "int64" , "index": true, "facet": true},
                {"name": "km_miles", "type": "string" , "index": true, "facet": false},
                {"name": "fuel_type", "type": "string" , "index": true, "facet": true},
                {"name": "fuel_city", "type": "string" , "index": true, "facet": false},
                {"name": "exterior_color", "type": "string" , "index": true, "facet": true},
                {"name": "interior_color", "type": "string" , "index": true, "facet": true},
                {"name": "price", "type": "int64" , "index": true, "facet": true},
                {"name": "hide_price", "type": "int32" , "index": true, "facet": false},
                {"name": "special_price_on", "type": "int32" , "index": true, "facet": false},
                {"name": "special_price", "type": "int64" , "index": true, "facet": true},
                {"name": "selling_price", "type": "int64" , "index": true, "facet": true},
                {"name": "special_price_type", "type": "string" , "index": true, "facet": false},
                {"name": "special_price_date", "type": "string" , "index": true, "facet": false},
                {"name": "show_stripe", "type": "int32" , "index": true, "facet": false},
                {"name": "financing_on", "type": "int32" , "index": true, "facet": false},
                {"name": "financing_special", "type": "int32" , "index": true, "facet": false},
                {"name": "downpayment", "type": "float" , "index": true, "facet": false},
                {"name": "emi_amount", "type": "float" , "index": true, "facet": false},
                {"name": "interest_rate", "type": "float" , "index": true, "facet": false},
                {"name": "term", "type": "float" , "index": true, "facet": false},
                {"name": "payment_frequency", "type": "string" , "index": true, "facet": false},
                {"name": "location", "type": "string" , "index": true, "facet": false},
                {"name": "created_at", "type": "int64" , "index": true, "facet": false},
                {"name": "updated_at", "type": "int64" , "index": true, "facet": false},
                {"name": "image_count", "type": "int32" , "index": true, "facet": false},
                {"name": "deleted_at", "type": "int64" , "index": true, "facet": false},
                {"name": "image_urls", "type": "string" , "index": true, "facet": false},
                {"name": "files", "type": "string" , "index": true, "facet": false},
                {"name": "make_rank", "type": "int64" , "index": true, "facet": false},
                {"name": "model_rank", "type": "int64" , "index": true, "facet": false},
                {"name": "page_url", "type": "string" , "index": true, "facet": false},
                {"name": "year_search", "type": "string" , "index": true, "facet": true},
                {"name": "status_rank", "type": "int64" , "index": true, "facet": false}
            ]
        }';
        (new Typesense())->create($collection_data);
    }

    public function down()
    {
    }
}
