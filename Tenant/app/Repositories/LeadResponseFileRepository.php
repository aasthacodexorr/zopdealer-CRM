<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Repositories\AdvanceRepository;
use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\VendorModel;
use Tenant\app\Entities\Vendor;
use Carbon\Carbon;
use Tenant\app\Models\LeadResponseFileModel;

/**
 * LeadResponseFileRepository CRUD and pagination for the LeadResponseFile
 */
class LeadResponseFileRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new LeadResponseFileModel());
        $this->entity = 'Tenant\app\Entities\LeadResponseFile';
        $this->entityLabel = 'Lead File';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'lead_response_file_id';
    }
    
    /**
     * filterFilesByLeadId
     *
     * @param  mixed $lead_id
     * @return 
     */
    public function filterFilesByLeadId($lead_id){
        return $this->model->where('lead_response_id', $lead_id)->asArray()->findAll();
    }
}
