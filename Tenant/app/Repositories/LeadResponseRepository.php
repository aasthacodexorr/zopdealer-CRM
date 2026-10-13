<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\AdvanceRepository;
use App\Repositories\BaseRepository;
use Tenant\app\Models\LeadResponseModel;
use Tenant\app\Entities\LeadResponse;
use Tenant\app\Entities\LeadResponseNote;
use Tenant\app\Libraries\UserAssignment\UserAssignmentFactory;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Models\LeadResponseNoteModel;

/**
 * LeadResponseRepository This repository has methods to create responses.
 */
class LeadResponseRepository extends AdvanceRepository
{
    const NOT_ENCRYPTED = 0;
    const ENCRYPTED = 1;
    const ENCRYPTION_NOT_REQUIRED = 2;

    public function __construct()
    {
        parent::__construct(new LeadResponseModel());
    }

    /**
     * create new lead response.
     *
     * @param  mixed $data
     * @return array
     */
    public function create($data): array
    {
        $leadResponseEntity = new LeadResponse($data);

        if ($this->model->save($leadResponseEntity) === false) {
            return $this->model->errors();
        } else {

            $lead_id = $this->model->insertID;
            return [
                'success' => 'Lead has been created successfully.',
                'lead_id' => $lead_id,
            ];
        }
    }


    /**
     * show This shows single instance of Lead Response
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array
    {

        $response = $this->model->asArray()->find($id);

        if (!empty($response)) {
            if ($response['is_encrypted'] == static::ENCRYPTED) {
                try {
                    $response['other_data'] = Utility::decryptString($response['other_data'], getenv("password.encryption"));
                } catch (\Exception $ex) {
                    log_message('warning', $ex->getMessage());
                }
            }
        }

        return $response;
    }


    /**
     * update Updates the Lead Response data with $id
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return array
     */
    public function update($id, $data)
    {

        $leadResponse = new LeadResponse($data);
        $leadResponse->id = $id;

        if ($this->model->save($leadResponse) === false) {
            return [
                $this->model->errors()
            ];
        } else {
            return [
                "success" => 'Lead Response has been updated.',
                'lead_id' => $id,
            ];
        }
    }



    /**
     * multipleUpdates Update multiple lead responses by ids.
     *
     * @param  mixed $ids
     * @param  mixed $data
     * @return void
     */
    public function multipleUpdates(array $ids, $data)
    {
        return $this->model->update($ids, $data);
    }

    /**
     * multipleDeletes Deletes multiple lead responses.
     *
     * @param  mixed $ids
     * @return void
     */
    public function multipleDeletes(array $ids)
    {
        return $this->model->delete($ids);
    }


    public function setConvertFlagForLead($lead_id, $flag): bool
    {
        return $this->model->protect(false)->update($lead_id, ['is_converted' => $flag]);
    }


    /**
     * saveNote Here the logic saves the note along with the attachment for the Lead Response
     *
     * @param  int $id
     * @param  mixed $data
     * @return array
     */
    public function saveNote(int $id, $data): array
    {
        $leadResponseNote = new LeadResponseNote($data);
        $leadResponseNote->lead_response_id = $id;
        $leadResponseNoteModel = new LeadResponseNoteModel();

        if ($leadResponseNoteModel->save($leadResponseNote) === false) {
            return $leadResponseNoteModel->errors();
        } else {

            $lead_note_id = $leadResponseNoteModel->insertID;
            return [
                'success' => 'Note for lead has been created',
                'lead_note_id' => $lead_note_id,
            ];
        }
    }


    /**
     * allNotes This function returns all the notes related to a lead response
     *
     * @param  mixed $id
     * @return array
     */
    public function allNotes(int $id): array
    {
        $notes = (new LeadResponseNoteModel())->where('lead_response_id', $id)->orderBy('created_at', 'DESC')->asArray()->findAll();
        return ['all_notes' => $notes];
    }

    /**
     * showNote This returns a single note by id.
     *
     * @param  mixed $id
     * @return array
     */
    public function showNote($id): ?array
    {
        return (new LeadResponseNoteModel())->asArray()->find($id);
    }

    /**
     * updateNote Update note. Accepts id and data to be updated.
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateNote($id, $data)
    {

        $leadResponseNote = new LeadResponseNote($data);
        $leadResponseNote->id = $id;
        $leadResponseNoteModel = new LeadResponseNoteModel();


        if ($leadResponseNoteModel->save($leadResponseNote) === false) {
            return [
                $leadResponseNoteModel->errors()
            ];
        } else {
            return [
                "success" => 'Lead Note has been updated.',
            ];
        }
    }

    /**
     * deleteNote Delete a note for the lead response 
     *
     * @param  mixed $id
     * @return array
     */
    public function deleteNote(int $id)
    {
        $note = $this->showNote($id);
        if ($note == null) {
            return ['errors' => 'Could not find the note'];
        }
        if (!empty($note['attachment'])) {
            $file_path = $note['attachment'];
            if (gettype($file_path) == "string") {
                CloudStorageFactory::getInstance()->delete($file_path);
            }
        }
        (new LeadResponseNoteModel())->delete($id);
        return ['success' => 'Note has been deleted successfully'];
    }


    public function allColumnAggregation($query, $status = false)
    {
        $columns = [
            ['name' => 'first_name', 'type' => null],
            ['name' => 'last_name', 'type' => null],
            ['name' => 'phone', 'type' => 'numeric'],
            ['name' => 'email', 'type' => null],
            ['name' => 'inventory_id', 'type' => 'numeric'],
            ['name' => 'stock_no', 'type' => null],
            ['name' => 'source', 'type' => null],
            ['name' => 'status', 'type' => null],
            ['name' => 'assignee', 'type' => 'numeric'],

        ];

        $result = [];
        foreach ($columns as $column) {
            if ($column['type'] == 'numeric') {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy(ABS($column['name']), 'ASC')->get()->getResultArray();
            } else {
                $this->allQueries($query);
                $result[$column['name']] = $this->model->select($column['name'] . ' as name')->selectCount($column['name'], 'count')->where("deleted_at", NULL)->groupBy($column['name'])->orderBy($column['name'], 'ASC')->get()->getResultArray();
            }
        }

        return $result;
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the get parameters for filter and search results
     * @param $data : $data recieved. 
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_first_name' || $key == 'zm_last_name' ||
                    $key == 'zm_email' || $key == 'zm_stock_no' ||
                    $key == 'zm_source' || $key == 'zm_status' ||
                    $key == 'zm_phone' || $key == 'zm_inventory_id' || $key == 'zm_assignee'

                ) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                } else if ($key == "zm_dis") {
                    $single_query = [
                        'header' => null,
                        'column' => 'created_at',
                        'join' => 'AND',
                        'comparator' => "less than",
                        "type" => "Relative Time Period Comparision",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                }
            }

            if (!empty($data['zm_date_start']) && !empty($data['zm_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "created_at",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }



    /**
     * updateSearchIndex
     *
     * @param  mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {

        try {

            $db = \Config\Database::connect();
            $builder = $db->table('lead_responses');

            $all_lead_responses = NULL;

            parent::setModel(new LeadResponseModel());

            if (empty($ids)) {
                $all_lead_responses =  $this->model
                    ->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_lead_responses = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_lead_responses as $key => $current_lead) {
                try {
                    $data['data'] = $current_lead;
                    $lead_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($current_lead['id'])) {
                        if (!empty($current_lead['stock_no'])) {
                            $inventory_details['data'] = (new InventoryRepository())->findSingleInventoryByStockId($current_lead['stock_no']);

                            if (!empty($inventory_details['data'])) {
                                $inventory_search_keyword = (new InventoryModel())->createKeyWordString($inventory_details);
                                $lead_with_updated_search_index['data']['search_keyword'] .= " " . $inventory_search_keyword['data']['search_keyword'];
                            }
                        }

                        $builder->where('id', $current_lead['id'])->set(['search_keyword' => $lead_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Lead search index has been updated.'];
    }

    public function setAssigneeByAutoAssignmentAlgorithm()
    {
        $user = '';
        try {
            $configuration = new ConfigurationRepository();
            $lead_auto_assignment_strategy = $configuration->findConfigurationColumnsByKey('lead_auto_assignment_strategy', ['value']);
            $lead_auto_assignment_strategy = $lead_auto_assignment_strategy->value;

            if (is_numeric($lead_auto_assignment_strategy)) {
                $user_assignment_strategy = UserAssignmentFactory::getInstance((int) $lead_auto_assignment_strategy);

                if (!empty($user_assignment_strategy)) {
                    $user = $user_assignment_strategy->assign();
                }
            }

            return $user;
        } catch (\Exception $th) {
            return '';
        }
    }

    public function encryptLeadResponses(): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('lead_responses');
        $builder->select('*');
        $builder->where('is_encrypted', static::NOT_ENCRYPTED);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            try {
                $other_data = $row['other_data'];

                if (empty($other_data)) {
                    (new LeadResponseModel())->update($row['id'], ['is_encrypted' => static::ENCRYPTION_NOT_REQUIRED]);
                    $this->updateSearchIndex([$row['id']]);
                } else {
                    (new LeadResponseModel())->update($row['id'], ['other_data' => Utility::encryptString($other_data, getenv("password.encryption")), 'is_encrypted' => static::ENCRYPTED]);
                    $this->updateSearchIndex([$row['id']]);
                }
            } catch (\Exception $ex) {
                log_message('warning', $ex->getMessage());
            }
        }
    }

    public function scheduleLeadOnCall($column, int $id)
    {
        $response =  $this->model->asArray()->where($this->model->table . '.' . $column . " > '" . $id . "' ")->first();

        if (!empty($response)) {
            if ($response['is_encrypted'] == static::ENCRYPTED) {
                try {
                    $response['other_data'] = Utility::decryptString($response['other_data'], getenv("password.encryption"));
                } catch (\Exception $ex) {
                    log_message('warning', $ex->getMessage());
                }
            }
        }

        return $response;
    }
}
