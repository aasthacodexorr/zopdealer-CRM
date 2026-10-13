<?php

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use CodeIgniter\Test\ControllerTester;
use Tenant\app\Models\LocationModel;
use Tenant\app\Repositories\LocationRepository;

class LocationTest extends \CodeIgniter\Test\FeatureTestCase
{
    protected $refresh = false;
    
    protected $namespace = ['App','Tenant/app'];

    public function setUp(): void
	{
		parent::setUp();
		$this->clean = false;
		$this->model = new LocationModel();
		$this->model->where("1 = 1")->delete();
		$this->model->purgeDeleted();
		$this->faker = Faker\Factory::create();
		$this->repository = new LocationRepository();
		$this->location_routes = [
			[ 'post', 'create', 'LocationController::create' ],
			[ 'get', 'getAllActiveLocation', 'LocationController::getAllActiveLocation' ]
		  ];
		
		$this->headers = [
			'CONTENT_TYPE' => 'application/json'
		];

		// Extra code to run before each test
    }
    
    /**
     * test_for_create_location Test whether the location is created
     *
     * @return void
     */
    public function test_create_location()
	{
		$location = $this->getLocation();
		$result = $this->withRoutes($this->location_routes)->post('create',$location);

		//test if request is successful
		$result->assertJSONFragment(['success' => 'Location has been created.']);
		$this->assertTrue($result->isOK());

		//check if the location is present in database.
		$this->seeInDatabase('locations', $location);


	}
	
	/**
	 * test_only_one_default_location Always only 1 location will be default
	 *
	 * @return void
	 */
	public function test_only_one_default_location(){
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(1));
		$this->seeNumRecords(1, 'locations', ['default' => 1]);

		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0));
		$this->seeNumRecords(1, 'locations', ['default' => 1]);

		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(1));
		$this->seeNumRecords(1, 'locations', ['default' => 1]);
			
	}

	public function test_always_fetch_one_location(){

		/***
		 * This condition is for the location set, not set default and no name passed to get the location
		 */
		$location = $this->getLocation(0);
		$this->withRoutes($this->location_routes)->post('create', $location);
		$result = $this->repository->getLocationByName("");
		$this->assertEquals($location['name'], $result['name']);


		/**
		 * This condition is for the location set, not set to default but location name is passed.
		 */
		$location = $this->getLocation(0);
		$this->withRoutes($this->location_routes)->post('create', $location);
		$result = $this->repository->getLocationByName($location['name']);
		$this->assertEquals($location['name'], $result['name']);


		/**
		 * This condition is for the location set, location set to default but no location passed,
		 * should return the default location set.
		 */
		$location = $this->getLocation(1);
		$this->withRoutes($this->location_routes)->post('create', $location);
		$result = $this->repository->getLocationByName("");
		$this->assertEquals($location['name'], $result['name']);


	}


	public function test_only_active_locations(){
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0));
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0));
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0));
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0));
		$this->withRoutes($this->location_routes)->post('create',$this->getLocation(0,0));

		

		//Show only show 4 locations since 5th location is 
		$result = $this->withRoutes($this->location_routes)->get('getAllActiveLocation');
		$result = json_decode($result->getJSON(), true);
		$this->assertCount(4, $result['locations']);


	}
	

	public function tearDown() : void
	{
		parent::tearDown();
		$this->model = new LocationModel();
		$this->model->where("1 = 1")->delete();
		$this->model->purgeDeleted();
		
	}


	private function getLocation($default = 0, $active = 1){
			
		return [
				'name' =>  preg_replace("/[^a-zA-Z0-9\s]/", "", $this->faker->name),
				'street' => str_replace("\n", "", $this->faker->address),
				'city' => $this->faker->city,
				'state' => $this->faker->state,
				'zipcode'	=> $this->faker->postcode,
				'country'	=> $this->faker->randomElement([
					'USA','Canada'
				]),
				'phone'		=> $this->faker->numberBetween(1000000000,9999999999),
				'email'		=> $this->faker->safeEmail,
				'dealer_registration_number' => $this->faker->randomElement([
					'', '565532813', '635-50-4266', 'PT6-W986', 'U1901128'
				]),
				'rin_number' => $this->faker->randomElement([
					'', '565532813', '635-50-4266', '571-56-5770', 'U1901128'
				]),
				'tax_number' => $this->faker->randomElement([
					'', '565532813', '635-50-4266', '571-56-5770', 'U1901128'
				]),
				'default' => $default,
				'status'  => $active
			];

	}
    


}