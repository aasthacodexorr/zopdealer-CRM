<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Tenant\app\Models\LocationModel;

/**
 * LocationRepository CRUD and pagination for the Vendor
 */
class LocationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new LocationModel());
        $this->entity = 'Tenant\app\Entities\Location';
        $this->entityLabel = 'Location';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'location_id';
    }

    public function updateMultiple(array $data, $condition_query = NULL, $condition_value = NULL)
    {
        try {
            if (isset($condition_query) && isset($condition_value)) {
                $this->model->where($condition_query, $condition_value);
            }

            $this->model->set($data)->update();
        } catch (DatabaseException $e) {
            $e->getMessage();
        }
    }

    /**
     * filterData This filers the data.
     *
     * @param  mixed $column
     * @param  mixed $value
     * @return object
     */
    public function filterData($column, $value)
    {
        $this->model->where($column, $value);
        return $this;
    }


    /**
     * getLocationByName This method returns the location by name. As a fallback uses the default.
     *
     * @param  mixed $location_name
     * @return array
     */
    public function getLocationByName(String $location_name = ""): array
    {
        try {

            $business = NULL;

            if (!empty($location_name)) {
                $business = $this->filterData('name', $location_name)->index(null, $header = null, null, null, null, null, false, 'location_view_id', 'locations');
            } else {
                $business = $this->filterData('default', 1)->index(null, $header = null, null, null, null, null, false, 'location_view_id', 'locations');
            }

            if (empty($business['locations'])) {
                $business = (new LocationRepository)->index(null, $header = null, null, null, null, null, false, 'location_view_id', 'locations');
            }

            $business = array_slice($business['locations'], 0, 1, false);

            return $business[0];
        } catch (\Exception $ex) {
            return [];
        }
    }
}
