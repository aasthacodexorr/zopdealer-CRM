<?php

namespace App\Repositories;

use App\Models\DealerTrackConfigurationModel;


/**
 * DealerTrackConfigurationRepository CRUD for wordpressJobView Model
 */
class DealerTrackConfigurationRepository extends CRUDWithAdvanceRepository
{

    public function __construct()
    {
        parent::__construct(new DealerTrackConfigurationModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Dealer Track Configuration';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'dealer_track_configuration_id';
    }

    public function createOrUpdate(array $data): array
    {
        try {

            if (empty($data['database'])) {
                throw new \Exception("Database Name is Missing.", 1);
            }

            $instance = $this->model->where('database', $data['database'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {

            return ["errors" => $e->getMessage()];
        }
    }

    public function getByDealerID(String $dealer_id): ?array
    {
        return $this->model->where('dealer_id', $dealer_id)->asArray()->first();
    }
}
 