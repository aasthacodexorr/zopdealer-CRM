<?php

use App\Filters\AuthFilter;
use App\Policies\AuthPolicy;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use CodeIgniter\Test\ControllerTester;
use Config\Services;
use Tenant\app\Models\VendorModel;
use Tenant\app\Repositories\VendorRepository;

class VendorTest extends \CodeIgniter\Test\FeatureTestCase
{
    protected $refresh = false;
    
    protected $namespace = ['App','Tenant/app'];

    public function setUp(): void
	{
		parent::setUp();
		$this->clean = false;
		$this->model = new VendorModel();
		$this->model->where("1 = 1")->delete();
		$this->model->purgeDeleted();
		$this->faker = Faker\Factory::create();
		$this->repository = new VendorRepository();
		$this->location_routes = [
			[ 'post', 'create', 'VendorController::create' ],
			[ 'get', 'show/(:alphanum)', 'VendorController::show/$1' ],
			[ 'post', 'update/(:alphanum)', 'VendorController::update/$1' ],
			['post','multipleDelete', 'VendorController::multipleDeletes'],
			['get','index', 'VendorController::index']
		  ];
		
		$this->headers = [
			'CONTENT_TYPE' => 'application/json'
		];
		$this->authPolicy = $this->getMockBuilder('App\Policies')
		->setMethods(['hasElevatedRoles'])
		->getMock();

		Services::injectMock('authPolicy', $this->authPolicy);
    }


    public function test_vendor_can_only_be_created_by_users_with_elevated_roles(){

		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);
        $result = $this->withRoutes($this->location_routes)->post('create', $this->getVendor());
		$result->assertJSONFragment(['success' => 'Vendor has been created.']);
		$result->isOK();
    }

	public function test_user_with_other_roles_cannot_create_vendor(){


		$this->authPolicy->method('hasElevatedRoles')->willReturn(false);

        $result = $this->withRoutes($this->location_routes)->post('create', $this->getVendor());
		$result->assertJSONFragment(['errors' => ERROR_RESOURCE_PERMISSION]);
		$result->assertDontSee('Vendor has been created.');
		$result->assertStatus(400);
		//$result->isOK();
	}

	public function test_user_with_elevated_roles_can_see_the_vendor_details(){
		$vendor = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);

		$result = $this->withRoutes($this->location_routes)->get('show/' . $vendor['vendor_id']);
		$result->assertJSONFragment(['id' => $vendor['vendor_id']]);

	}

	public function test_user_with_other_roles_cannot_see_the_vendor_details(){
		$vendor = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(false);

		$result = $this->withRoutes($this->location_routes)->get('show/' . $vendor['vendor_id']);
		$result->assertJSONFragment(['errors' => ERROR_RESOURCE_PERMISSION]);

	}

	public function test_user_with_elevated_roles_can_update_the_vendor_details(){
		$vendor = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);
		$new_vendor_details = $this->getVendor();
		$new_vendor_details['id'] = $vendor['vendor_id'];
		$result = $this->withRoutes($this->location_routes)->post('update/' . $vendor['vendor_id'],$new_vendor_details);
		
		$result->assertJSONFragment(['success' => 'Vendor has been updated.']);
		$result->isOK();
	}

	public function test_vendor_cannot_be_updated_if_not_in_the_system(){
		$vendor = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);
		$new_vendor_details = $this->getVendor();
		$new_vendor_details['id'] = $vendor['vendor_id'];
		$result = $this->withRoutes($this->location_routes)->post('update/' . '0',$new_vendor_details);
		
		$result->assertStatus(400);
		$result->assertJSONFragment(['errors' => "Vendor couldn't be found."]);
	}

	public function test_user_with_other_roles_cannot_update_the_vendor_details(){
		$vendor = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(false);
		$new_vendor_details = $this->getVendor();
		$new_vendor_details['id'] = $vendor['vendor_id'];
		$result = $this->withRoutes($this->location_routes)->post('update/' . $vendor['vendor_id'],$new_vendor_details);
		
		$result->assertJSONFragment(['errors' => ERROR_RESOURCE_PERMISSION]);
		$result->assertStatus(400);

	}

	public function test_user_with_elevated_roles_can_delete_multiple_vendors(){
		$vendor_1 = $this->repository->create($this->getVendor());
		$vendor_2 = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);
		$result = $this->withRoutes($this->location_routes)->post('multipleDelete',['vendor_ids' => $vendor_1['vendor_id'] .','.$vendor_2['vendor_id'] ]);
		$result->isOK();

		$find_vendor_1 = $this->repository->show($vendor_1['vendor_id']);
		$this->assertNull($find_vendor_1);

		$find_vendor_2 = $this->repository->show($vendor_2['vendor_id']);
		$this->assertNull($find_vendor_2);
	}

	public function test_user_with_other_roles_cannot_delete_multiple_vendors(){
		$vendor_1 = $this->repository->create($this->getVendor());
		$vendor_2 = $this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(false);
		$result = $this->withRoutes($this->location_routes)->post('multipleDelete',['vendor_ids' => $vendor_1['vendor_id'] .','.$vendor_2['vendor_id'] ]);
		$result->assertJSONFragment(['errors' => ERROR_RESOURCE_PERMISSION]);
		$result->assertStatus(400);

	}

	public function test_user_with_elevated_roles_can_see_all_the_vendors(){
		$this->repository->create($this->getVendor());
		$this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(true);
		$result = $this->withRoutes($this->location_routes)->get('index');
		$result->assertJSONFragment(['header' => null]);
		$result->assertJSONFragment(['pager' => [ 'total' => 2]]);
	}

	public function test_user_with_other_roles_cannot_see_all_the_vendors(){
		$this->repository->create($this->getVendor());
		$this->repository->create($this->getVendor());
		$this->authPolicy->method('hasElevatedRoles')->willReturn(false);
		$result = $this->withRoutes($this->location_routes)->get('index');
		$result->assertJSONFragment(['errors' => ERROR_RESOURCE_PERMISSION]);
		$result->assertStatus(400);
	}
	

	public function tearDown() : void
	{
		parent::tearDown();
		$this->model = new VendorModel();
		$this->model->where("1 = 1")->delete();
		$this->model->purgeDeleted();
		
	}


	private function getVendor(){
			
		return [
				'company_name' => $this->faker->company,
				'first_name' => preg_replace("/[^a-zA-Z0-9\s]/", "", $this->faker->firstName),
                'last_name' => preg_replace("/[^a-zA-Z0-9\s]/", "", $this->faker->lastName),
                'rin' => $this->faker->randomElement([
					'', '565532813', '635-50-4266', '571-56-5770', 'U1901128'
				]),
                'tax_number' => $this->faker->randomElement([
					'', '565532813', '635-50-4266', '571-56-5770', 'U1901128'
				]),
				'street' => str_replace("\n", "", $this->faker->address),
                'apt' => $this->faker->buildingNumber,
				'city' => $this->faker->city,
				'province' => $this->faker->state,
				'postal_code'	=> $this->faker->postcode,
				'country'	=> $this->faker->randomElement([
					'USA','Canada'
				]),
				'phone'		=> $this->faker->numberBetween(1000000000,9999999999),
                'mobile'		=> $this->faker->numberBetween(1000000000,9999999999),
                'fax'		=> $this->faker->numberBetween(1000000000,9999999999),
				'email'		=> $this->faker->safeEmail
			];

	}
    


}