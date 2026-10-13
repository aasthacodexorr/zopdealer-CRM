<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Libraries\CloudStorage\CloudStorageFactory;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\CustomerModel;
use Tenant\app\Entities\LeadResponseNote;
use Tenant\app\Models\CustomerNoteModel;


/**
 * CustomerRepository CRUD and pagination for the Customer
 */
class CustomerRepository extends CRUDWithAdvanceRepository
{
    const NOT_ENCRYPTED = 0;
    const ENCRYPTED = 1;
    const ENCRYPTION_NOT_REQUIRED = 2;

    public function __construct()
    {
        parent::__construct(new CustomerModel());
        $this->entity = 'Tenant\app\Entities\Customer';
        $this->entityLabel = 'Customer details';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'customer_id';
    }

    /**
     * show This shows single instance of Customer
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
                    $response = $this->getDecryptedCustomer($response);
                } catch (\Exception $ex) {
                    log_message('warning', $ex->getMessage());
                }
            }
        }

        return $response;
    }


    /**
     * saveWithOutValidation
     *
     * @param  mixed $data
     * @return 
     */
    public function saveWithOutValidation(array $data)
    {
        return $this->model->protect(false)->insert($data, true);
    }


    /**
     * @method prepareDataForFilter : prepares data recieved.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_assignee'

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
     * saveNote Here the logic saves the note along with the attachment for the customer
     *
     * @param  int $id
     * @param  mixed $data
     * @return array
     */
    public function saveNote(int $id, $data): array
    {
        $customerNote = new LeadResponseNote($data);
        $customerNote->customer_id = $id;
        $customerNoteModel = new CustomerNoteModel();

        if ($customerNoteModel->save($customerNote) === false) {
            return $customerNoteModel->errors();
        } else {

            $customer_note_id = $customerNoteModel->insertID;
            return [
                'success' => 'Note for customer has been created.',
                'customer_note_id' => $customer_note_id,
            ];
        }
    }


    /**
     * allNotes This function returns all the notes related to a customer
     *
     * @param  mixed $id
     * @return array
     */
    public function allNotes(int $id): array
    {
        $notes = (new CustomerNoteModel())->where('customer_id', $id)->orderBy('created_at', 'DESC')->asArray()->findAll();
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
        return (new CustomerNoteModel())->asArray()->find($id);
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

        $customerNote = new LeadResponseNote($data);
        $customerNote->id = $id;
        $customerNoteModel = new CustomerNoteModel();


        if ($customerNoteModel->save($customerNote) === false) {
            return [
                $customerNoteModel->errors()
            ];
        } else {
            return [
                "success" => 'Customer Note has been updated.',
            ];
        }
    }

    /**
     * deleteNote Delete a note for the lead response 
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteNote(int $id)
    {
        $note = $this->showNote($id);
        if ($note == null) {
            return ['errors' => 'Could not find the note.'];
        }
        if (!empty($note['attachment'])) {
            $file_path = $note['attachment'];
            if (gettype($file_path) == "string") {
                CloudStorageFactory::getInstance()->delete($file_path);
            }
        }
        (new CustomerNoteModel())->delete($id);
        return ['success' => 'Customer Note has been deleted successfully.'];
    }

    public function parseTemplate(array $customer, string $description, array $additional_mapping = []): string
    {
        try {

            $mapping = [
                '{First Name}' => $customer['first_name'],
                '{Full Name}' => $customer['first_name'] . ' ' . $customer['last_name']
            ];

            foreach (array_merge($mapping, $additional_mapping) as $variable => $value) {
                $description = str_replace($variable, $value, $description);
            }
            return $description;
        } catch (\Exception $ex) {
            return $description;
        }
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
            $builder = $db->table('customers');

            $all_customers = NULL;

            parent::setModel(new CustomerModel());

            if (empty($ids)) {
                $all_customers =  $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_customers = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_customers as $key => $value) {
                try {
                    $data['data'] = $value;
                    $customer_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])->set(['search_keyword' => $customer_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Customer search index has been updated.'];
    }

    public function encryptCustomers(): void
    {
        $db = \Config\Database::connect();
        $builder = $db->table('customers');
        $builder->select('*');
        $builder->where('is_encrypted', static::NOT_ENCRYPTED);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            try {
                $columns_to_encrypt = ['driver_licence', 'additional_information', 'date_of_birth', 'other_data'];
                $modified_data = [];
                foreach ($columns_to_encrypt as $column) {
                    if (!empty($row[$column])) {
                        try {
                            $modified_data[$column] = Utility::encryptString($row[$column], getenv("password.encryption"));
                        } catch (\Exception $ex) {
                        }
                    }
                }

                if (!empty($modified_data)) {
                    $modified_data['is_encrypted'] = static::ENCRYPTED;
                    (new CustomerModel())->protect(false)->update($row['id'], $modified_data);
                }
            } catch (\Exception $ex) {
                log_message('warning', $ex->getMessage());
            }
        }
    }

    private function getDecryptedCustomer(array $data): array
    {
        $columns_to_decrypt = ['driver_licence', 'additional_information', 'date_of_birth', 'other_data'];
        foreach ($columns_to_decrypt as $column) {
            if (!empty($data[$column])) {
                try {
                    $data[$column] = Utility::decryptString($data[$column], getenv("password.encryption"));
                } catch (\Exception $ex) {
                }
            }
        }

        $data['is_encrypted'] = static::NOT_ENCRYPTED;

        return $data;
    }
}
