<?php

namespace Tenant\app\Libraries\Mapper;

use App\Interfaces\MapperInterface;

/**
 * Facebook This maps the fields in Facebook Market Place by exact match or fuzzy match.
 */
class Msn implements MapperInterface
{

    /**
     * body_types
     *
     * @var array
     */
    private $body_types = [
        "convertible"   =>  "Convertible",
        "coupe"         =>  "Coupe",
        "coupe2door"    =>  "Coupe",
        "sedan"         =>  "Sedan",
        "hatchback"     =>  "Hatchback",
        "wagon"         =>  "Wagon",
        "suvcrossover"  =>  "SUV",
        "pickuptruck"   =>  "Truck",
        "truck"         =>  "Truck",
        "minivanvan"    =>  "Minivan",
        "otherdontknow" =>  "Sedan",
        //  "van"           => "Van",          
    ];

    /**
     * fuel_types
     *
     * @var array
     */
    private $fuel_types = [
        "diesel"            =>  "Diesel",
        "gasolinefuel"      =>  "Gasoline",
        "hybridgaselectric" =>  "Hybrid",
        "otherdontknow"     =>  "Gasoline",
        "flexfuel"          =>  "Gasoline",
        "otherdontknow"     =>  "Gasoline",
        "propane"           =>  "Gasoline",
        //    "electric" => "Electric", 
        //    "hydrogen" => "Hydrogen", 
        //    "cng" => "CNG",
    ];

    /**
     * drivetrain
     *
     * @var array
     */
    private $drivetrain = [
        "4wheeldrive" => "Four wheel drive",
        "4x4" => "Four wheel drive",
        "allwheeldrive" => "All wheel drive",
        "frontwheeldrive" => "Front wheel drive",
        "rearwheeldrive" => "Rear wheel drive",
        "otherdontknow" => "All wheel drive",
    ];

    /**
     * exactMatch This method matches exact value of dropdown in facebook.
     *
     * @param  string $array_variable
     * @param  mixed $input
     * @param  mixed $default
     * @return String
     */
    public function exactMatch(string $array_variable, $input, $default = ""): String
    {
        if (empty($this->$array_variable)) {
            throw new \Exception("Mapping for " . $array_variable . " not found.", 1);
        }

        $sanitze_input =    strtolower($input);
        $sanitze_input =    preg_replace('/[^[:alnum:]]/', '', $sanitze_input);

        return empty($this->$array_variable[$sanitze_input]) ? $default : $this->$array_variable[$sanitze_input];
    }
}
