<?php

namespace Tenant\app\Libraries\Mapper;

use App\Interfaces\MapperInterface;

/**
 * Craigslist This maps the fields in Craigslist by exact match or fuzzy match.
 */
class Craigslist implements MapperInterface
{

    /**
     * cylinders
     *
     * @var array
     */
    private $cylinders = [
        '3' => '3 cylinders',
        '4' => '4 cylinders',
        '5' => '5 cylinders',
        '6' => '6 cylinders',
        '8' => '8 cylinders',
        '10' => '10 cylinders',
        '12' => '12 cylinders'
    ];

    /**
     * drivetrains
     *
     * @var array
     */
    private $drivetrains = [
        '4wheeldrive'   => '4wd',
        '4x4'           => '4wd',
        'allwheeldrive'   => '4wd',
        'frontwheeldrive'   => 'fwd',
        'rearwheeldrive'    => 'rwd',
        'otherdontknow'     => '-'

    ];


    /**
     * body_types
     *
     * @var array
     */
    private $body_types = [
        "commercialev"   =>  "other",
        "convertible"         =>  "convertible",
        "coupe"    =>  "coupe",
        "coupe2door"     =>  "coupe",
        "cargovan"    =>  "van",
        "golfcartsev" =>  "other",
        "hatchback"   =>  "hatchback",
        "minivanvan"         =>  "mini-van",
        "otherdontknow"  =>  "other",
        "pickuptruck"         =>  "pickup",
        "passengervan"         =>  "van",
        "sedan"     =>  "sedan",
        "suv"       => "SUV",
        "suvcrossover"  => "SUV",
        "trailer"   => "other",
        "truck"     => "truck",
        "wagon"     => "wagon",
        "van"    =>  "van"
    ];


    /**
     * fuel_types
     *
     * @var array
     */
    private $fuel_types = [
        "diesel"    =>  "diesel",
        "flexfuel"  =>  "hybrid",
        "gasolinefuel"  =>  "gas",
        "hybridgaselectric"     =>  "hybrid",
        "otherdontknow"     =>  "other",
        "propane"   =>  "other",
        "electricfuelsystem" => "electric",
        "electricbattery" => "electric",
        "electric" => "electric",
        "regular" => "gas",
        "premium" => "gas",
        "regularunleaded" => "gas",
        "premiumrequired" => "gas",
        "flexfuelpremiumrecommendedunleadedrequired" => "other",
        "flexfuelregular" => "other",
        "gasolinemildelectrichybrid" => "hybrid",
        "flexfuelcapability" => "other",
        "gaselectrichybrid" => "hybrid",
        "pluginelectricgas" => "hybrid",
        "dieselfuel" => "diesel",
        "hybrid" => "hybrid",
        "premiumunleadedrecommended" => "gas",
        "flexfuelunleadede85" => "other"


    ];


    /**
     * exterior_colors
     *
     * @var array
     */
    private $exterior_colors = [
        "black"     =>  "black",
        "grey"      =>  "grey",
        "silver"    =>  "silver",
        "white"     =>  "white",
        "cream"     =>  "custom",
        "tan"       =>  "custom",
        "brown"     =>  "brown",
        "maroon"    =>  "custom",
        "red"       =>  "red",
        "orange"    =>  "custom",
        "gold"      =>  "custom",
        "yellow"    =>  "yellow",
        "green"     =>  "green",
        "teal"      =>  "custom",
        "blue"      =>  "blue",
        "purple"    =>  "purple"
    ];


    /**
     * disclosure_brand_types
     *
     * @var array
     */
    private $disclosure_brand_types = [
        "rebuilt"  => "rebuilt",
        "salvage"   => "salvage",
        "clean"   => "clean",
        "na"   => "clean",
        "none"   => "clean",
    ];


    private $transmissions = [
        "automatic"     => "automatic",
        "cvt"     => "automatic",
        "manual"        =>  "manual"
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
