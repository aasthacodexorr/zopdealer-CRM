<?php

namespace Tenant\app\Libraries\Mapper;

use App\Interfaces\MapperInterface;

/**
 * Facebook This maps the fields in Facebook Market Place by exact match or fuzzy match.
 */
class Facebook implements MapperInterface
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
        "hatchback"     =>  "Hatchback",
        "minivanvan"    =>  "Minivan",
        "otherdontknow" =>  "Other",
        "pickuptruck"   =>  "Truck",
        "sedan"         =>  "Sedan",
        "suvcrossover"  =>  "SUV",
        "truck"         =>  "Truck",
        "wagon"         =>  "Wagon"
    ];


    /**
     * fuel_types
     *
     * @var array
     */
    private $fuel_types = [
        "diesel"    =>  "Diesel",
        "flexfuel"  =>  "Flex",
        "gasolinefuel"  =>  "Gasoline",
        "hybridgaselectric"     =>  "Hybrid",
        "otherdontknow"     =>  "Other",
        "propane"   =>  "Other",
        "electricfuelsystem" => "Electric",
        "electricbattery" => "Electric",
        "electric" => "Electric",
        "regular" => "Gasoline",
        "premium" => "Gasoline",
        "regularunleaded" => "Gasoline",
        "premiumrequired" => "Gasoline",
        "flexfuelpremiumrecommendedunleadedrequired" => "Flex",
        "flexfuelregular" => "Flex",
        "gasolinemildelectrichybrid" => "Hybrid",
        "flexfuelcapability" => "Flex",
        "gaselectrichybrid" => "Hybrid",
        "pluginelectricgas" => "Hybrid",
        "dieselfuel" => "Diesel",
        "hybrid" => "Hybrid",
        "premiumunleadedrecommended" => "Gasoline",
        "flexfuelunleadede85" => "Flex",
        "premiumunleadedrequired" => "Gasoline",
        "gasoline" => "Gasoline"
    ];


    /**
     * exterior_colors
     *
     * @var array
     */
    private $exterior_colors = [
        "black"     =>  "Black",
        "grey"      =>  "Gray",
        "silver"    =>  "Silver",
        "white"     =>  "White",
        "cream"     =>  "Other",
        "tan"       =>  "Tan",
        "brown"     =>  "Brown",
        "maroon"    =>  "Other",
        "red"       =>  "Red",
        "orange"    =>  "Orange",
        "gold"      =>  "Gold",
        "yellow"    =>  "Yellow",
        "green"     =>  "Green",
        "teal"      =>  "Other",
        "blue"      =>  "Blue",
        "purple"    =>  "Purple"
    ];


    /**
     * interior_colors
     *
     * @var array
     */
    private $interior_colors = [
        "black"     =>  "Black",
        "grey"      =>  "Gray",
        "silver"    =>  "Silver",
        "white"     =>  "White",
        "cream"     =>  "Other",
        "tan"       =>  "Tan",
        "brown"     =>  "Brown",
        "maroon"    =>  "Other",
        "red"       =>  "Red",
        "orange"    =>  "Orange",
        "gold"      =>  "Gold",
        "yellow"    =>  "Yellow",
        "green"     =>  "Green",
        "teal"      =>  "Other",
        "blue"      =>  "Blue",
        "purple"    =>  "Purple"
    ];


    /**
     * makes
     *
     * @var array
     */
    private $makes = [
        "acura" => "Acura",
        "alfaromeo" => "Alfa Romeo",
        "astonmartin" => "Aston Martin",
        "audi" => "Audi",
        "bmw" => "BMW",
        "bentley" => "Bentley",
        "buick" => "Buick",
        "coda" => "CODA",
        "cadillac" => "Cadillac",
        "chevrolet" => "Chevrolet",
        "chrysler" => "Chrysler",
        "daewoo" => "Daewoo",
        "daihatsu" => "Daihatsu",
        "dodge" => "Dodge",
        "eagle" => "Eagle",
        "ferrari" => "Ferrari",
        "fiat" => "Fiat",
        "fisker" => "Fisker",
        "ford" => "Ford",
        "freightliner" => "Freightliner",
        "gmc" => "GMC",
        "genesis" => "Genesis",
        "geo" => "Geo",
        "honda" => "Honda",
        "hummer" => "Hummer",
        "hyundai" => "Hyundai",
        "infiniti" => "Infiniti",
        "isuzu" => "Isuzu",
        "jaguar" => "Jaguar",
        "jeep" => "Jeep",
        "kia" => "Kia",
        "lamborghini" => "Lamborghini",
        "landrover" => "Land Rover",
        "lexus" => "Lexus",
        "lincoln" => "Lincoln",
        "lotus" => "Lotus",
        "maserati" => "Maserati",
        "maybach" => "Maybach",
        "mazda" => "Mazda",
        "mclaren" => "Mclaren",
        "mercedesbenz" => "Mercedes-Benz",
        "mercury" => "Mercury",
        "mini" => "Mini",
        "mitsubishi" => "Mitsubishi",
        "nissan" => "Nissan",
        "oldsmobile" => "Oldsmobile",
        "panoz" => "Panoz",
        "plymouth" => "Plymouth",
        "pontiac" => "Pontiac",
        "porsche" => "Porsche",
        "ram" => "Ram",
        "rivian" => "Rivian",
        "rollsroyce" => "Rolls-Royce",
        "saab" => "Saab",
        "saturn" => "Saturn",
        "scion" => "Scion",
        "smart" => "Smart",
        "srt" => "Srt",
        "subaru" => "Subaru",
        "suzuki" => "Suzuki",
        "tesla" => "Tesla",
        "toyota" => "Toyota",
        "volkswagen" => "Volkswagen",
        "volvo" =>  "Volvo"
    ];

    /**
     * models
     *
     * @var array
     */
    private $models = [
        "rdx"  =>  "RDX",
        "tlx"  =>  "TLX",
        "ilx"  =>  "ILX",
        "mdx"  =>  "MDX",
        "nsx"  =>  "NSX",
        "rlx"  =>  "RLX",
        "4c"  =>  "4C",
        "giulia"  =>  "Giulia",
        "stelvio"  =>  "Stelvio",
        "db11"  =>  "DB11",
        "vanquish"  =>  "Vanquish",
        "r8"  =>  "R8",
        "a3"  =>  "A3",
        "a4"  =>  "A4",
        "a5"  =>  "A5",
        "a6"  =>  "A6",
        "a7"  =>  "A7",
        "a8"  =>  "A8",
        "etron"  =>  "e-tron",
        "q3"  =>  "Q3",
        "q5"  =>  "Q5",
        "q7"  =>  "Q7",
        "q8"  =>  "Q8",
        "rs3"  =>  "RS3",
        "s3"  =>  "S3",
        "s4"  =>  "S4",
        "s5"  =>  "S5",
        "sq5"  =>  "SQ5",
        "tt"  =>  "TT",
        "z4"  =>  "Z4",
        "x7"  =>  "X7",
        "x6"  =>  "X6",
        "x5"  =>  "X5",
        "x4"  =>  "X4",
        "x3"  =>  "X3",
        "x2"  =>  "X2",
        "x1"  =>  "X1",
        "series8"  =>  "8 Series",
        "series6"  =>  "6 Series",
        "series5"  =>  "5 Series",
        "series3"  =>  "3 Series",
        "series2"  =>  "2 Series",
        "series4"  =>  "4 Series",
        "series7"  =>  "7 Series",
        "8series"  => "8 Series",
        "7series"  => "7 Series",
        "6series"  => "6 Series",
        "5series"  => "5 Series",
        "4series"  => "4 Series",
        "3series"  => "3 Series",
        "2series"  => "2 Series",
        "m6"  =>  "M6",
        "m5"  =>  "M5",
        "m4"  =>  "M4",
        "m2"  =>  "M2",
        "i8"  =>  "i8",
        "i3"  =>  "i3",
        "continental"  =>  "Continental",
        "bentayga"  =>  "Bentayga",
        "flyingspur"  =>  "Flying Spur",
        "mulsanne"  =>  "Mulsanne",
        "regal"  =>  "Regal",
        "lacrosse"  =>  "LaCrosse",
        "envision"  =>  "Envision",
        "encore"  =>  "Encore",
        "enclave"  =>  "Enclave",
        "buickcascada"  =>  "Buick Cascada",
        "ct6"   =>  "CT6",
        "cadillacxt6"   =>  "Cadillac XT6",
        "ats"   =>  "ATS",
        "cts"   =>  "CTS",
        "escalade"   =>  "Escalade",
        "escaladeesv"   =>  "Escalade ESV",
        "cadillacxts"   =>  "Cadillac XTS",
        "xt4"   =>  "XT4",
        "xt5"   =>  "XT5",
        "equinox"   =>  "Equinox",
        "malibu"   =>  "Malibu",
        "silverado2500"   =>  "Silverado 2500",
        "silverado3500"   =>  "Silverado 3500",
        "tahoe"   =>  "Tahoe",
        "traverse"   =>  "Traverse",
        "blazer"   =>  "Blazer",
        "boltev"   =>  "Bolt EV",
        "camaro"   =>  "Camaro",
        "colorado"   =>  "Colorado",
        "corvette"   =>  "Corvette",
        "cruze"   =>  "Cruze",
        "express"   =>  "Express",
        "expresscargo"   =>  "Express Cargo",
        "expresscutaway"   =>  "Express Cutaway",
        "impala"   =>  "Impala",
        "silverado1500"   =>  "Silverado 1500",
        "sonic"   =>  "Sonic",
        "spark"   =>  "Spark",
        "suburban"   =>  "Suburban",
        "trax"   =>  "Trax",
        "volt"   =>  "Volt",
        "300"   =>  "300",
        "pacifica"   =>  "Pacifica",
        "200"   =>  "200",
        "challenger"   =>  "Challenger",
        "charger"   =>  "Charger",
        "durango"   =>  "Durango",
        "grandcaravan"   =>  "Grand Caravan",
        "journey"   =>  "Journey",
        "sprinter2500"   =>  "Sprinter 2500",
        "488gtb"   =>  "488 GTB",
        "488spider"   =>  "488 Spider",
        "812superfast"   =>  "812 Superfast",
        "gtc4"   =>  "GTC4",
        "portofino"   =>  "Portofino",
        "124spider"   =>  "124 Spider",
        "500"   =>  "500",
        "500abarth"   =>  "500 Abarth",
        "500l"   =>  "500L",
        "500x"   =>  "500X",
        "500cabarth"   =>  "500C Abarth",
        "500c"   =>  "500C",
        "escape"   =>  "Escape",
        "expedition"   =>  "Expedition",
        "explorer"   =>  "Explorer",
        "f150"   =>  "F150 Regular Cab",
        "econolinevan"   =>  "Econoline Van",
        "ecosport"   =>  "EcoSport",
        "edge"   =>  "Edge",
        "f250superduty"   =>  "F250 Super Duty Regular Cab",
        "f350"   =>  "F350 Super Duty Regular Cab",
        "f450"   =>  "F450 Super Duty Regular Cab & Chassis",
        "f550"   =>  "F550 Super Duty Regular Cab & Chassis",
        "fiesta"   =>  "Fiesta",
        "flex"   =>  "Flex",
        "fusion"   =>  "Fusion",
        "mustang"   =>  "Mustang",
        "ranger"   =>  "Ranger",
        "taurus"   =>  "Taurus",
        "transit"   =>  "Transit",
        "transitconnect"   =>  "Transit Connect",
        "sprinter3500xd"   =>  "Sprinter 3500XD",
        "sprinter2500"   =>  "Sprinter 2500",
        "sprinterworker"   =>  "Sprinter WORKER",
        "terrain"   =>  "Terrain",
        "yukon"   =>  "Yukon",
        "acadia"   =>  "Acadia",
        "canyon"   =>  "Canyon",
        "savanacutaway"   =>  "Savana Cutaway",
        "sierra"   =>  "Sierra",
        "savana2500"   =>  "Savana 2500",
        "savana3500"   =>  "Savana 3500",
        "genesisg70"   =>  "Genesis G70",
        "genesisg80"   =>  "Genesis G80",
        "genesisg90"   =>  "Genesis G90",
        "odyssey"   =>  "Odyssey",
        "pilot"   =>  "Pilot",
        "accord"   =>  "Accord",
        "civic"   =>  "Civic",
        "clarity"   =>  "Clarity",
        "crv"   =>  "CR-V",
        "fit"   =>  "Fit",
        "hrv"   =>  "HR-V",
        "insight"   =>  "Insight",
        "passport"   =>  "Passport",
        "ridgeline"   =>  "Ridgeline",
        "accent"   =>  "Accent",
        "elantra"   =>  "Elantra",
        "kona"   =>  "Kona",
        "santafe"   =>  "Santa Fe",
        "veloster"   =>  "Veloster",
        "ioniq"   =>  "Ioniq",
        "nexo"   =>  "NEXO",
        "sonata"   =>  "Sonata",
        "tucson"   =>  "Tucson",
        "qx60"   =>  "QX60",
        "q50"   =>  "Q50",
        "q60"   =>  "Q60",
        "q70"   =>  "Q70",
        "qx30"   =>  "QX30",
        "qx50"   =>  "QX50",
        "qx60"   =>  "QX60",
        "qx80"   =>  "QX80",
        "ftype"   =>  "F-TYPE",
        "epace"   =>  "E-PACE",
        "fpace"   =>  "F-PACE",
        "ipace"   =>  "I-PACE",
        "xe"   =>  "XE",
        "xf"   =>  "XF",
        "xj"   =>  "XJ",
        "jeepgladiator"   =>  "Jeep Gladiator",
        "grandcherokee"   =>  "Grand Cherokee",
        "wrangler"   =>  "Wrangler",
        "wranglerunlimited"   =>  "Wrangler Unlimited",
        "cherokee"   =>  "Cherokee",
        "compass"   =>  "Compass",
        "grandcherokee"   =>  "Grand Cherokee",
        "renegade"   =>  "Renegade",
        "optima"   =>  "Optima",
        "soul"   =>  "Soul",
        "sportage"   =>  "Sportage",
        "kiatelluride"   =>  "Kia Telluride",
        "cadenza"   =>  "Cadenza",
        "forte"   =>  "Forte",
        "kiak900"   =>  "Kia K900",
        "niro"   =>  "Niro",
        "rio"   =>  "Rio",
        "sedona"   =>  "Sedona",
        "sorento"   =>  "Sorento",
        "stinger"   =>  "Stinger",
        "aventador"   =>  "Aventador",
        "huracan"   =>  "Huracan",
        "rangeroverevoque"   =>  "Range Rover Evoque",
        "discovery"   =>  "Discovery",
        "discoverysport"   =>  "Discovery Sport",
        "rangerover"   =>  "Range Rover",
        "rangeroversport"   =>  "Range Rover Sport",
        "rangerovervelar"   =>  "Range Rover Velar",
        "nx"   =>  "NX",
        "rc"   =>  "RC",
        "es"   =>  "ES",
        "lexusgx"   =>  "Lexus GX",
        "gs"   =>  "GS",
        "is"   =>  "IS",
        "lc"   =>  "LC",
        "lx"   =>  "LX",
        "rx"   =>  "RX",
        "ux"   =>  "UX",
        "aviator"   =>  "Aviator",
        "ls"   =>  "LS",
        "lincolnmkt"   =>  "Lincoln MKT",
        "mkc"   =>  "MKC",
        "mkz"   =>  "MKZ",
        "nautilus"   =>  "Nautilus",
        "navigator"   =>  "Navigator",
        "evora"   =>  "Evora",
        "ghibli"   =>  "Ghibli",
        "levante"   =>  "Levante",
        "cx3"   =>  "CX-3",
        "cx5"   =>  "CX-5",
        "cx9"   =>  "CX-9",
        "mazda3"   =>  "Mazda3",
        "mazda6"   =>  "Mazda6",
        "mx5"   =>  "MX-5",
        "gle"   =>  "GLE",
        "aclass"   =>  "A-Class",
        "amgcclass"   =>  "AMG C-Class",
        "amgcla"   =>  "AMG CLA",
        "amgcls"   =>  "AMG CLS",
        "amgeclass"   =>  "AMG E-Class",
        "amggclass"   =>  "AMG G-Class",
        "amggla"   =>  "AMG GLA",
        "amgglc"   =>  "AMG GLC",
        "amgglccoupe"   =>  "AMG GLC Coupe",
        "amggle"   =>  "AMG GLE",
        "amgglecoupe"   =>  "AMG GLE Coupe",
        "amggls"   =>  "AMG GLS",
        "amgsclass"   =>  "AMG S-Class",
        "amgsl"   =>  "AMG SL",
        "amgslc"   =>  "AMG SLC",
        "amggt"   =>  "AMG GT",
        "cclass"   =>  "C-Class",
        "claclass"   =>  "CLA-Class",
        "cls"   =>  "CLS",
        "eclass"   =>  "E-Class",
        "gclass"   =>  "G-Class",
        "glaclass"   =>  "GLA-Class",
        "glccoupe"   =>  "GLC Coupe",
        "glcclass"   =>  "GLC-Class",
        "gle"   =>  "GLE",
        "glsclass"   =>  "GLS-Class",
        "maybachsclass"   =>  "Maybach S-Class",
        "metris"   =>  "Metris",
        "metrisworker"   =>  "Metris Worker",
        "sclass"   =>  "S-Class",
        "slclass"   =>  "SL-Class",
        "slcclass"   =>  "SLC-Class",
        "sprinter"   =>  "Sprinter",
        "clubman"   =>  "Clubman",
        "cooper"   =>  "Cooper",
        "countryman"   =>  "Countryman",
        "eclipsecross"   =>  "Eclipse Cross",
        "outlander"   =>  "Outlander",
        "mirage"   =>  "Mirage",
        "outlandersport"   =>  "Outlander Sport",
        "370z"   =>  "370Z",
        "altima"   =>  "Altima",
        "armada"   =>  "Armada",
        "frontier"   =>  "Frontier",
        "gtr"   =>  "GT-R",
        "kicks"   =>  "Kicks",
        "leaf"   =>  "LEAF",
        "maxima"   =>  "Maxima",
        "murano"   =>  "Murano",
        "nv"   =>  "NV",
        "nv200"   =>  "NV200",
        "Pathfinder"   =>  "Pathfinder",
        "rogue"   =>  "Rogue",
        "sentra"   =>  "Sentra",
        "titan"   =>  "Titan",
        "titanxd"   =>  "Titan XD",
        "versa"   =>  "Versa",
        "porsche718boxster"   =>  "Porsche 718 Boxster",
        "porsche718cayman"   =>  "Porsche 718 Cayman",
        "911"   =>  "911",
        "cayenne"   =>  "Cayenne",
        "macan"   =>  "Macan",
        "panamera"   =>  "Panamera",
        "1500"   =>  "1500",
        "2500"   =>  "2500",
        "3500"   =>  "3500",
        "4500"   =>  "4500",
        "5500"   =>  "5500",
        "promaster"   =>  "Promaster",
        "dawn"   =>  "Dawn",
        "ghost"   =>  "Ghost",
        "phantom"   =>  "Phantom",
        "wraith"   =>  "Wraith",
        "fortwo"   =>  "Fortwo",
        "subaruascent"   =>  "Subaru Ascent",
        "brz"   =>  "BRZ",
        "subarucrosstrek"   =>  "Subaru Crosstrek",
        "forester"   =>  "Forester",
        "impreza"   =>  "Impreza",
        "legacy"   =>  "Legacy",
        "outback"   =>  "Outback",
        "wrx"   =>  "WRX",
        "model3"   =>  "Model 3",
        "models"   =>  "Model S",
        "modelx"   =>  "Model X",
        "corolla"   =>  "Corolla",
        "prius"   =>  "Prius",
        "sienna"   =>  "Sienna",
        "4runner"   =>  "4Runner",
        "86"   =>  "86",
        "avalon"   =>  "Avalon",
        "chr"   =>  "C-HR",
        "camry"   =>  "Camry",
        "camryhybrid"   =>  "Camry Hybrid",
        "highlander"   =>  "Highlander",
        "landcruiser"   =>  "Landcruiser",
        "mirai"   =>  "Mirai",
        "priusc"   =>  "Prius c",
        "rav4"   =>  "RAV4",
        "sequoia"   =>  "Sequoia",
        "tacoma"   =>  "Tacoma",
        "tundra"   =>  "Tundra",
        "yaris"   =>  "Yaris",
        "arteon"   =>  "Arteon",
        "atlas"   =>  "Atlas",
        "beetle"   =>  "Beetle",
        "golf"   =>  "Golf",
        "golfr"   =>  "Golf R",
        "jetta"   =>  "Jetta",
        "passat"   =>  "Passat",
        "tiguan"   =>  "Tiguan",
        "volkswagenegolf"   =>  "Volkswagen e-Golf",
        "xc40"   =>  "XC40",
        "xc90"   =>  "XC90",
        "s60"   =>  "S60",
        "s90"   =>  "S90",
        "v90"   =>  "V90",
        "xc60"   =>  "XC60"
    ];

    /**
     * drivetrain
     *
     * @var array
     */
    private $drivetrain = [
        "4wheeldrive" => "4X2",
        "4x4" => "4X4",
        "allwheeldrive" => "AWD",
        "frontwheeldrive" => "FWD",
        "otherdontknow" => "Other",
        "rearwheeldrive" => "RWD"
    ];

    /**
     * vehicle_type
     *
     * @var array
     */
    private $vehicle_type = [
        "boat"   => "boat",
        "commercialev" => "commercial",
        "convertible" => "car_truck",
        "coupe" => "car_truck",
        "coupe2door" => "car_truck",
        "cargovan"  => "other",
        "dump"  => "other",
        "golfcartsev"  => "other",
        "hatchback"   => "car_truck",
        "minivanvan"  => "car_truck",
        "otherdontknow"  => "other",
        "pickuptruck"  => "car_truck",
        "passengervan"  => "commercial",
        "sedan"  => "car_truck",
        "suv"  => "car_truck",
        "suvcrossover" => "car_truck",
        "trailer"  => "trailer",
        "truck" => "car_truck",
        "wagon"  => "car_truck"
    ];

    /**
     * transmission
     *
     * @var array
     */
    private $transmission = [
        "automatic"      =>  "Automatic",
        "cvt"      =>  "Automatic",
        "manual"         =>  "Manual",
        "other"          =>  "Manual",
        "triptronic"     =>  "Manual"
    ];


    /**
     * state_of_vehicle
     *
     * @var array
     */
    private $state_of_vehicle = [
        "new"      =>  "New",
        "used"         =>  "Used",
        "demo"          =>  "Used",
        "asis"     =>  "Used"
    ];

    /**
     * milegae units
     *
     * @var array
     */
    private $mileage_types = [
        "km"      =>  "KM",
        "miles"   =>  "MI"
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
