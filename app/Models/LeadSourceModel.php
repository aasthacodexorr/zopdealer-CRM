<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Handling leads from multiple sources
 */
class LeadSourceModel extends Model
{
    protected $table = 'fbleads';
    protected $primaryKey = 'id';
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $allowedFields = [
        'retailer_id', 'creation_time', 'leadgen_id', 'platform',
        'campaign_name', 'ad_name', 'form_id', 'data', 'full_name',
        'email', 'phone', 'status', 'client_id', 'message', 'source',
        'provider_name'
    ];

    protected $useSoftDeletes = false;
    protected $returnType = 'App\Entities\LeadSource';
    protected $useTimestamps = false;

    protected $validationRules = [
        'source' => [
            "label" => 'Lead Source',
            "rules" => 'required'
        ],
    ];

    /**
     * Get unprocessed leads for all sources
     */
    public function getUnprocessedLeads(int $limit = 100): array
    {
        return $this->where('status', 0)
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get unprocessed leads by source
     */
    public function getUnprocessedLeadsBySource(string $source, int $limit = 100): array
    {
        return $this->where('source', $source)
            ->where('status', 0)
            ->orderBy('id', 'ASC')
            ->limit($limit)
            ->findAll();
    }


}
