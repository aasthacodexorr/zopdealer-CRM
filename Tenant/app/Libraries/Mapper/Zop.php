<?php

namespace Tenant\app\Libraries\Mapper;

use App\Interfaces\MapperInterface;

/**
 * Zop This maps the fields in Zop by exact match or fuzzy match.
 */
class Zop implements MapperInterface
{


    private $makes = [
        "agt" => "AGT",
        "amgeneral" => "AM General",
        "amc" => "AMC",
        "acura" => "Acura",
        "alcomcargopro" => "Alcom Cargo Pro",
        "alfaromeo" => "Alfa Romeo",
        "aluma" => "Aluma",
        "astonmartin" => "Aston Martin",
        "atlascopco" => "Atlas Copco",
        "audi" => "Audi",
        "austinhealey" => "Austin Healey",
        "bluewater" => "BLUEWATER",
        "bmw" => "BMW",
        "bayliner" => "Bayliner",
        "bentley" => "Bentley",
        "bricklin" => "Bricklin",
        "bugatti" => "Bugatti",
        "buick" => "Buick",
        "budd" => "Budd",
        "cjaytrailers" => "CJay Trailers",
        "cadillac" => "Cadillac",
        "capacity" => "Capacity",
        "cargoexpress" => "Cargo Express",
        "carrier" => "Carrier",
        "caterpillar" => "Caterpillar",
        "cedarrapids" => "Cedar Rapids",
        "chevrolet" => "Chevrolet",
        "chrysler" => "Chrysler",
        "clubcar" => "Club Car",
        "daewoo" => "Daewoo",
        "daihatsu" => "Daihatsu",
        "datsun" => "Datsun",
        "dodge" => "Dodge",
        "ducati" => "Ducati",
        "ezgo" => "E-Z-GO",
        "eagle" => "Eagle",
        "excavating" => "Excavating",
        "fiat" => "FIAT",
        "ferrari" => "Ferrari",
        "fleetwood" => "FLEETWOOD",
        "fisker" => "Fisker",
        "freightliner" => "Freightliner",
        "ford" => "Ford",
        "gmc" => "GMC",
        "genesis" => "Genesis",
        "genix" => "Genix",
        "geo" => "Geo",
        "hummer" => "HUMMER",
        "harleydavidson" => "Harley-Davidson",
        "haulin" => "Haulin",
        "heartland" => "Heartland",
        "hino" => "Hino",
        "honda" => "Honda",
        "hyundai" => "Hyundai",
        "infiniti" => "Infiniti",
        "international" => "International",
        "internationalharvester" => "International Harvester",
        "isuzu" => "Isuzu",
        "jaguar" => "Jaguar",
        "jc" => "JC",
        "jeep" => "Jeep",
        "kawasaki" => "Kawasaki",
        "kenworth" => "Kenworth",
        "klm" => "KLM",
        "kia" => "Kia",
        "king" => "King",
        "komatsu" => "Komatsu",
        "lamborghini" => "Lamborghini",
        "landrover" => "Land Rover",
        "lexus" => "Lexus",
        "lincoln" => "Lincoln",
        "lotus" => "Lotus",
        "mack" => "MACK",
        "mg" => "MG",
        "mini" => "MINI",
        "mv" => "MV",
        "mack" => "Mack",
        "maserati" => "Maserati",
        "mastercraft" => "Mastercraft",
        "maybach" => "Maybach",
        "mazda" => "Mazda",
        "mclaren" => "McLaren",
        "mercedesbenz" => "Mercedes-Benz",
        "mercury" => "Mercury",
        "mitsubishi" => "Mitsubishi",
        "mobilityventures" => "Mobility Ventures",
        "nissan" => "Nissan",
        "oasis" => "Oasis",
        "oldsmobile" => "Oldsmobile",
        "opel" => "Opel",
        "other" => "Other",
        "pjtrailers" => "PJ Trailers",
        "paceamerican" => "Pace American",
        "panoz" => "Panoz",
        "peterbilt" => "Peterbilt",
        "peugeot" => "Peugeot",
        "plymouth" => "Plymouth",
        "polaris" => "Polaris",
        "pontiac" => "Pontiac",
        "porsche" => "Porsche",
        "ram" => "Ram",
        "rainbow" => "Rainbow",
        "reefer" => "Reefer",
        "renault" => "Renault",
        "rolloff" => "Roll-off",
        "rollsroyce" => "Rolls-Royce",
        "saab" => "Saab",
        "saturn" => "Saturn",
        "scion" => "Scion",
        "shelby" => "Shelby",
        "smart" => "Smart",
        "southland" => "Southland",
        "sprinter" => "SPRINTER",
        "spyker" => "Spyker",
        "sterling" => "Sterling",
        "stronghaul" => "Stronghaul",
        "studebaker" => "Studebaker",
        "subaru" => "Subaru",
        "suzuki" => "Suzuki",
        "taylor" => "Taylor",
        "terex" => "Terex",
        "tesla" => "Tesla",
        "tgb" => "TGB",
        "thermoking" => "Thermo King",
        "thruway" => "Thru-Way",
        "toyota" => "Toyota",
        "trailmobile" => "Trailmobile",
        "triumph" => "Triumph",
        "utility" => "Utility",
        "volkswagen" => "Volkswagen",
        "volvo" => "Volvo",
        "westernstar" => "Western Star",
        "yamaha" => "Yamaha",
        "yugo" => "Yugo"
    ];
    /**
     * body_types
     *
     * @var array
     */
    private $body_types = [
        "car" => "Car",
        "truck" => "Truck",
        "suv" => "SUV",
        "van" => "Minivan-Van",
        "sportutilityvehicle" => "SUV",
        "pickuptruck" =>"Pickup-Truck",
        "pickup" => "Pickup-Truck",
        "sportutilityvehiclevehicle" => "SUV",
        "4dsedanat" => "Sedan",
    ];

    /**
     * drivetrains
     *
     * @var array
     */
    private $drivetrains = [
        "fwd" => "Front Wheel Drive",
        "awd" => "All Wheel Drive",
        "rwd" => "Rear Wheel Drive",
        "4wd" => "4-Wheel Drive",
        "frontwheeldrive" => "Front Wheel Drive",
        "allwheeldrive" => "All Wheel Drive",
        "rearwheeldrive" => "Rear Wheel Drive",
        "fourwheeldrive" => "4-Wheel Drive",
    ];

    /**
     * transmissions
     *
     * @var array
     */
    private $transmissions = [
        "automatic" => "Automatic",
        "cvt" => "CVT",
        "manual" => "Manual",
    ];

    /**
     * cylinders
     *
     * @var array
     */
    private $cylinders = [
        "1" => "1 Cylinder Engine",
        "2" => "2 Cylinder Engine",
        "3" => "3 Cylinder Engine",
        "4" => "4 Cylinder Engine",
        "6" => "6 Cylinder Engine",
        "8" => "8 Cylinder Engine",
        "10" => "10 Cylinder Engine",
        "12" => "12 Cylinder Engine",
    ];

    /**
     * doors
     *
     * @var array
     */
    private $doors = [
        "2" => "2 Door",
        "3" => "3 Door",
        "4" => "4 Door",
        "5" => "5 Door",
        "6" => "6 Door",
        "7" => "7 Door",
        "8" => "8 Door",
    ];



    /**
     * passengers
     *
     * @var array
     */
    private $passengers = [
        "2" =>  "2 Seater",
        "3" =>  "3 Seater",
        "4" =>  "4 Seater",
        "5" =>  "5 Seater",
        "6" =>  "6 Seater",
        "7" =>  "7 Seater",
        "8" =>   "8 Seater",
    ];

    /**
     * statuses
     *
     * @var array
     */
    private $statuses = [
        "instock" => "Instock",
        "sold" => "Sold",
        "customerfile" => "Sold",
        "unpublished" => "Unpublished",
        "pendingtrade" => "Unpublished",
        "loaner" => "Unpublished",
        "fleet"  => "Unpublished"

    ];


    /**
     * sub_statuses
     *
     * @var array
     */
    private $sub_statuses = [
        "availableforsale" => "Available For Sale",
        "reconditioning" => "Reconditioning",
        "atauction" => "At Auction",
        "comingsoon" => "Coming Soon",
        "dealpending" => "Deal Pending",
        "delivered" => "Delivered",
        'onorder'   => "Coming Soon",
        'customerfile'   => "Delivered",
        'pendingtrade'   => "Coming Soon",

    ];

    /**
     * vehicle_types
     *
     * @var array
     */
    private $vehicle_types = [
        "new" => "New",
        "used" => "Used",
        "demo" => "Demo",
        "asis" => "As-is",
        "consignment" => "Demo",
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
