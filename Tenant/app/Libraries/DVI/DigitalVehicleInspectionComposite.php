<?php

namespace Tenant\app\Libraries\DVI;

use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Repositories\DigitalVehicleInspectionItemRepository;
use Tenant\app\Repositories\DigitalVehicleInspectionRepository;
use Tenant\app\Repositories\DigitalVehicleInspectionTemplateRepository;
use Tenant\app\Repositories\InventoryRepository;

final class DigitalVehicleInspectionComposite
{

    private DigitalVehicleInspectionTemplateRepository $dvi_template_repository;
    private DigitalVehicleInspectionRepository $dvi_repository;
    private DigitalVehicleInspectionItemRepository $dvi_item_repository;

    public function __construct()
    {
        $this->dvi_template_repository = new DigitalVehicleInspectionTemplateRepository();
        $this->dvi_repository = new DigitalVehicleInspectionRepository();
        $this->dvi_item_repository = new DigitalVehicleInspectionItemRepository();
    }

    public function clone(String $template_id, String $stock_no, $assignee_id): ?array
    {
        $dvi_template = $this->dvi_template_repository->show($template_id);
        if (empty($dvi_template['id'])) {
            return [
                'error' => 'DVI Template Not Found'
            ];
        }

        unset($dvi_template['id']);
        $dvi_template['stock_no'] = $stock_no;
        $dvi_template['status'] = DigitalVehicleInspectionConstant::INCOMPLETE;
        $dvi_template['assignee'] = $assignee_id;

        unset($dvi_template['created_at']);
        unset($dvi_template['updated_at']);

        $dvi = $this->dvi_repository->create($dvi_template);
        if (empty($dvi['digital_vehicle_inspection_id'])) {
            return [
                'error' => 'DVI Template Not Found'
            ];
        }

        if (!empty($dvi_template['items'])) {
            foreach ($dvi_template['items'] as $key => $item) {
                $item['dvi_id'] = $dvi['digital_vehicle_inspection_id'];
                unset($item['id']);

                $item['render_only'] = $item['type'] === "HEADER" ? "1" : "0";
                $item['comment'] = $item['reason'] = $item['status'] = '';
                $this->dvi_item_repository->create($item);
            }
        }

        return $dvi;
    }

    public function render(String $id)
    {
        $dvi = $this->dvi_repository->show($id);
        if (!empty($dvi['stock_no'])) {
            $dvi['inventory'] = (new InventoryRepository())->findSingleInventoryByStockId($dvi['stock_no']);
        }

        if (!empty($dvi['items'])) {
            foreach ($dvi['items'] as $key => $item) {
                $dvi['items'][$key]['attachments'] = $this->dvi_item_repository->getAllAttachments($item['id']);
            }
        }

        return $dvi;
    }

    public function partialUpdate($id, $data): ?array
    {
        $validation =  \Config\Services::validation();
        $errors = [];

        $dvi = $this->dvi_repository->show($id);
        $has_modification_authorization_errors = $this->hasModificationAuthorizationErrors($dvi);

        if (!empty($has_modification_authorization_errors)) {
            return $this->hasModificationAuthorizationErrors($dvi);
        }

        $dvi_model_validations = $this->setValidationConditionsForDVI($data, $dvi);
        $dvi = array_merge($dvi, $data);

        $item_validation_flag = [];
        $item_validation_list = [];

        if ($dvi['status'] === DigitalVehicleInspectionConstant::COMPLETE) {
            foreach ($dvi['items'] as $item) {
                $item_validation =  \Config\Services::validation(null, false);
                $item_model_validation_rules = $this->setValidationConditionForDVIItem($item, $item);
                $item_validation->setRules($item_model_validation_rules);
                if (!$item_validation->run($item)) {
                    array_push($item_validation_flag, false);
                    array_push($item_validation_list, $item_validation->getErrors());
                } else {
                    array_push($item_validation_flag, true);
                    array_push($item_validation_list, $item_validation->getErrors());
                }
            }

            if (in_array(false, $item_validation_flag)) {
                $item_validation_list = array_filter(
                    $item_validation_list,
                    function ($item_validation) {
                        return !empty($item_validation);
                    }
                );

                $errors['items'] = (object) $item_validation_list;
            }
        }


        $validation->setRules($dvi_model_validations);
        if (!$validation->run($dvi)) {
            $errors = array_merge($errors, $validation->getErrors());
        }

        if (!empty($errors)) {
            return $errors;
        }


        return $this->dvi_repository->update($id, $data);
    }

    public function fetchItem($dvi_id, $item_id): ?array
    {
        $dvi = $this->dvi_repository->show($dvi_id);
        if (empty($dvi)) {
            return [
                "errors" => "DVI couldn't be found."
            ];
        }

        $dvi_item = $this->dvi_item_repository->show($item_id);
        if (empty($dvi_item)) {
            return [
                "errors" => "DVI Item couldn't be found."
            ];
        }

        if ($dvi_item['dvi_id'] !== $dvi_id) {
            return [
                "errors" => "Item didn't match with the DVI report."
            ];
        }

        $dvi_item['attachments'] = $this->dvi_item_repository->getAllAttachments($item_id);
        return $dvi_item;
    }

    public function updateItem($dvi_id, $item_id, $data): ?array
    {
        $validation =  \Config\Services::validation();
        $dvi = $this->dvi_repository->show($dvi_id);
        $has_modification_authorization_errors = $this->hasModificationAuthorizationErrors($dvi);

        if (!empty($has_modification_authorization_errors)) {
            return $this->hasModificationAuthorizationErrors($dvi);
        }


        $dvi_item = $this->dvi_item_repository->show($item_id);
        if (empty($dvi_item)) {
            return [
                "errors" => "DVI Item couldn't be found."
            ];
        }

        if ($dvi_item['dvi_id'] !== $dvi_id) {
            return [
                "errors" => "Item didn't match with the DVI report."
            ];
        }

        $dvi_item_model_validations = $this->setValidationConditionForDVIItem($data, $dvi_item);
        $template_keys = ['dvi_id', 'id', 'title', 'enable_comment', 'enable_attachments', 'type', 'description', 'has_response', 'render_only', 'deleted_at'];
        foreach ($template_keys as $template_key) {
            if (isset($data[$template_key])) {
                unset($data[$template_key]);
            }
        }

        $updated_dvi_item = array_merge($dvi_item, $data);
        $validation->setRules($dvi_item_model_validations);

        $has_passed_validations = $validation->run($updated_dvi_item);

        if ($has_passed_validations) {
            $data['has_response'] = '1';
        } else {
            $data['has_response'] = '0';
        }

        $response = $this->dvi_item_repository->update($item_id, $data);
        $this->dvi_repository->update($dvi_id, [
            'issues_count' => $this->dvi_repository->getFailedItemCount($dvi_id)
        ]);

        if ($has_passed_validations) {
            return $response;
        } else {
            return $validation->getErrors();
        }
    }

    public function saveItemAttachment($dvi_id, $item_id, $file): ?array
    {
        $dvi = $this->dvi_repository->show($dvi_id);
        $has_modification_authorization_errors = $this->hasModificationAuthorizationErrors($dvi);

        if (!empty($has_modification_authorization_errors)) {
            return $this->hasModificationAuthorizationErrors($dvi);
        }

        $item_response = $this->fetchItem($dvi_id, $item_id);
        if (empty($item_response['id'])) {
            return $item_response;
        }

        $name = $file->getRandomName();
        $file_path =  DVI_FILE . '/' . md5(db_connect()->database) . '/' . $name;
        CloudStorageFactory::getInstance()->save($file, $file_path);

        $data = [];
        $data['url'] = "/" . $file_path;
        $data['dvi_item_id'] = $item_id;

        return $this->dvi_item_repository->saveAttachment($data);
    }

    public function deleteItemAttachment($dvi_id, $item_id, $attachment_id): ?array
    {
        $dvi = $this->dvi_repository->show($dvi_id);
        $has_modification_authorization_errors = $this->hasModificationAuthorizationErrors($dvi);

        if (!empty($has_modification_authorization_errors)) {
            return $this->hasModificationAuthorizationErrors($dvi);
        }

        $item_response = $this->fetchItem($dvi_id, $item_id);
        if (empty($item_response['id'])) {
            return $item_response;
        }

        $attachments = empty($item_response['attachments']) ? [] : $item_response['attachments'];
        $attachments = array_filter($attachments, function ($attachment) use (&$attachment_id) {
            return $attachment['id'] == $attachment_id;
        });


        if (empty($attachments)) {
            return [
                "errors" => "DVI Item didn't match the Attachment ID."
            ];
        } else {
            $this->dvi_item_repository->deleteAttachment($attachment_id);
            return [
                'success' => 'DVI Item attachment has been deleted'
            ];
        }
    }



    private function setValidationConditionForDVIItem(array $payload_data, array $original_data): ?array
    {
        $dvi_item_model_validations = $this->dvi_item_repository->getModel()->getValidationRules();

        if (!empty($original_data['render_only'])) {
            return $dvi_item_model_validations;
        }

        switch ($original_data['type']) {
            case DigitalVehicleInspectionConstant::PASSFAIL:
                $dvi_item_model_validations['status'] = [
                    'label' => 'Status',
                    'rules' => 'required|in_list[' . DigitalVehicleInspectionConstant::PASS . ',' . DigitalVehicleInspectionConstant::FAIL . ']',
                    'errors' => [
                        'in_list' => 'Status should be either ' . DigitalVehicleInspectionConstant::PASS . ' or ' . DigitalVehicleInspectionConstant::FAIL
                    ]
                ];
                break;

            case DigitalVehicleInspectionConstant::PASSFAILWITHNA:
                $dvi_item_model_validations['status'] = [
                    'label' => 'Status',
                    'rules' => 'required|in_list[' . DigitalVehicleInspectionConstant::PASS . ',' . DigitalVehicleInspectionConstant::FAIL . ',' . DigitalVehicleInspectionConstant::NA . ']',
                    'errors' => [
                        'in_list' => 'Status should be either ' . DigitalVehicleInspectionConstant::PASS . ', ' . DigitalVehicleInspectionConstant::FAIL . ', ' . DigitalVehicleInspectionConstant::NA
                    ]
                ];
                break;

            case DigitalVehicleInspectionConstant::COMMENT:
                $dvi_item_model_validations['comment'] = [
                    'label' => 'Comment',
                    'rules' => 'required',
                ];

                break;
            default:
                # code...
                break;
        }

        if (!empty($payload_data['status'])) {
            if ($payload_data['status'] === DigitalVehicleInspectionConstant::FAIL) {
                $dvi_item_model_validations['reason'] = [
                    'label' => 'Reason',
                    'rules' => 'required',
                ];
            }
        }


        return $dvi_item_model_validations;
    }

    private function setValidationConditionsForDVI(array $payload_data, array $original_data): ?array
    {
        $dvi_model_validations = $this->dvi_repository->getModel()->getValidationRules();

        foreach ($payload_data as $key => $value) {
            switch ($key) {
                case 'odometer':
                    if ($original_data['require_odometer'] == "1") {
                        $dvi_model_validations['odometer']['rules'] = "required|is_natural";
                    }
                    break;

                case 'status':
                    if ($payload_data['status'] === DigitalVehicleInspectionConstant::COMPLETE) {
                        if ($original_data['require_odometer'] == "1") {
                            $dvi_model_validations['odometer']['rules'] = "required|is_natural";
                        }

                        if (!empty($original_data['items'])) {
                            $total_respondable_items = array_reduce($original_data['items'], function ($carry, $item) {
                                if (empty($item['render_only'])) {
                                    return ++$carry;
                                }

                                return $carry;
                            }, 0);

                            $responded_items = array_reduce($original_data['items'], function ($carry, $item) {
                                if (!empty($item['has_response'])) {
                                    return ++$carry;
                                }

                                return $carry;
                            }, 0);

                            if ($responded_items < $total_respondable_items) {
                                $dvi_model_validations['status'] = [
                                    'label' => 'Status',
                                    'rules' => 'required|in_list[' . DigitalVehicleInspectionConstant::INCOMPLETE . ',' . DigitalVehicleInspectionConstant::COMPLETE . ',' .
                                        DigitalVehicleInspectionConstant::ARCHIVE . ']',
                                    'errors' => [
                                        'in_list' => 'You cannot mark the DVI as COMPLETE without responding to all the questions and Odometer (If required)'
                                    ]
                                ];
                            }
                        }
                    }

                    break;
                default:
                    break;
            }
        }

        return $dvi_model_validations;
    }

    private function hasModificationAuthorizationErrors($dvi): ?array
    {

        if (empty($dvi)) {
            return [
                "errors" => "DVI couldn't be found."
            ];
        }

        if ($dvi['status'] === DigitalVehicleInspectionConstant::COMPLETE) {
            return [
                "errors" => "DVI has already been completed and can no longer be modified."
            ];
        }

        return null;
    }
}
