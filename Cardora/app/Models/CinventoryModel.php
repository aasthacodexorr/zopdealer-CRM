<?php
namespace Cardora\app\Models;

use CodeIgniter\Model;

class CinventoryModel extends Model
{

    protected $table      = 'carinv';
    protected $primaryKey = 'carinv_id';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';


    // protected $searchableColumns = [
    //     'year', 'make', 'model', 'trim', 'odometer',
    //     'price', 'exterior_color', 'interior_color', 'transmission',
    //     'body', 'drivetrain', 'stockid', 'vin', 'engine'
    // ];

    protected $allowedFields = [       
        'dealer_id','dealer_name','address','city','state','postalcode','phone','email','vin','stockid', 'year', 'make', 'model', 
        'trim', 'body','transmission', 'drivetrain','doors', 'engine', 'exterior_color',
        'interior_color', 'price','odometer','fuel_type','is_used', 'is_certified','number_of_passengers','comments','video_url','images'
    ];

    protected $useSoftDeletes = false;
    protected $returnType    = 'Cardora\app\Entities\Cinventory';
    protected $useTimestamps = false;

    protected $validationRules    = [
        'make' => [
            "label" => 'Make',
            "rules" => 'required'
        ],
        'model' => [
            "label" => 'Model',
            "rules" => 'required'
        ],
        'year'  => [
            "label" => 'Year',
            "rules" => 'required'
        ],
        'price'  => [
            "label" => 'Price',
            "rules" => 'required'
        ],
        'stockid' => [
            "label" => 'Stock Number',
            "rules" => 'required'
        ],
        'dealer_id' => [
            "label" => 'Dealer ID',
            "rules" => 'required'
        ],
        'dealer_name' => [
            "label" => 'Dealer Name',
            "rules" => 'required'
        ],
        'price' => [
            "label" => 'Price',
            "rules" => 'required'
        ],                    
            

    ];

}
