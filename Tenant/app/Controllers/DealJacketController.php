<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\DealJacketRepository;
use App\Libraries\CloudStorage\CloudStorageFactory;
use mikehaertl\pdftk\Pdf;

class DealJacketController extends \App\Controllers\BaseController
{
    /**
     * repository
     *
     * @var DealJacketRepository
     */
    private $repository;

    /**
     * secondary_repository
     *
     * @var DealRepository
     */
    private $secondary_repository;

    /**
     * secondary_repository
     *
     * @var DealController
     */
    private $deal_controller;

    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->repository = new DealJacketRepository();
        $this->secondary_repository = new DealRepository();
        $this->configuration_repository = new ConfigurationRepository();
        $this->deal_controller = new DealController();

    }

    public function create($deal_id)
    {
        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $this->isLocked($deal);

        $validated = $this->validate([
            'file' => [
                'label' => 'File',
                'rules' => 'uploaded[file]|mime_in[file,application/pdf,image/png,image/jpeg,image/jpg,image/webp]|max_size[file,32000]',
            ],
        ]);

        if (!$validated) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $this->validator->getErrors()])->send();
            exit;
        }

        $file = $this->request->getFile('file');
        $name = $deal_id . '_' . $file->getClientName();
        $remote_file_path = ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name;

        CloudStorageFactory::getSecureInstance()->save($file, $remote_file_path);

        $data = [];
        $data["url"] = "/" . $remote_file_path;
        $data["type"] = $this->request->getPost('type');
        $data["section_type"] = $this->request->getPost('section_type');
        $data["source"] = $name;
        $data["deal_id"] = $deal_id;
        $data["user"] = service('authPolicy')->id;

        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function partialUpdate($deal_id, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $this->isLocked($deal);

        $data = $this->request->getPost();

        if (isset($data['column']) && $data['column'] === 'url') {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ['column' => 'Updating the URL column is not allowed.']])->send();
            exit;
        }

        $validated = $this->validate([
            'column' => [
                'label' => 'Column',
                'rules' => 'required',
            ],
            'value' => [
                'label' => 'Value',
                'rules' => 'permit_empty',
            ],
        ]);

        if (!$validated) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $this->validator->getErrors()])->send();
            exit;
        }

        $document = $this->repository->show($id);

        if (empty($document)) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Deal Jacket Document couldn't be found for this deal."])->send();
            exit;
        }

        $updateData = [];
        $updateData[$data['column']] = $data['value'];

        $response = $this->repository->update($id, $updateData);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function updateTagging($deal_id, $id) {
        $data = $this->request->getPost();

        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $this->isLocked($deal);

        $validated = $this->validate([
            'tagging' => [
                'label' => 'Tagging',
                'rules' => 'required|valid_json',
            ],
        ]);

        if($validated) {

            $tagging = json_decode($data["tagging"], true);
            $current_document = $this->repository->show($id);
            $documents = $this->repository->findByDealId($deal_id);
            CloudStorageFactory::getSecureInstance()->get("/" .  $current_document['url']);
            $local_saved_file_location = ROOTPATH . '/public/' . $current_document['url'];

            try {
                if (is_file($local_saved_file_location)) {
                    foreach ($tagging as $tag) {
                        //var_dump($tag);
                        //var_dump($documents);

                        if(empty($tag['pages']) || empty($tag['document_type'])){
                            continue;
                        }

                        $matched_documents = array_filter($documents, function ($document) use ($tag) {
                            return $document['type'] === $tag['document_type'] && $document['section_type'] === $tag['section_type'];
                        });

                        $matched_document = reset($matched_documents);

                        if ($matched_document !== false) {
                           $this->repository->delete($matched_document['id']);
                        }

                        $saved_pdf = new Pdf($local_saved_file_location);
                        $tagged_pdf = $saved_pdf->cat($tag['pages'])->execute();
                        if ($tagged_pdf) {
                            $tagged_pdf_remote_path = ROOT_FILE . '/' . md5(db_connect()->database) . '/'  . $deal_id .'_' . Utility::slugify($tag['document_type'], '_') . Utility::uniqueNumberBasedString()  .'.pdf';
                            CloudStorageFactory::getSecureInstance()->save($saved_pdf->getTmpFile(),  $tagged_pdf_remote_path);
                            $this->repository->create(['deal_id' => $deal_id,
                                'section_type' => $tag['section_type'],
                                'source' => 'Pages ' . implode(', ', $tag['pages']) . ' of ' . $current_document['source'],
                                'type' => $tag['document_type'],
                                'user' => service('authPolicy')->id,
                                'url' => '/' . $tagged_pdf_remote_path,
                            ]);
                        }


                    }

                }
            } catch (\Exception $exception) {
               log_message('error', $exception->getMessage());

            } finally {
                if (is_file($local_saved_file_location)) {
                    unlink($local_saved_file_location);
                }
            }
            //Save the tagging information
            //Extract Pages based on the tagging information
            //Delete document based on the mapping and resave it with the new information

            //Success
            $response = $this->repository->update($id, ['tagging' => $data['tagging'], 'has_tagging' => 1]);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }

        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function getDocumentWithEncryptedURL($deal_id, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $this->isLocked($deal);

        $document = $this->repository->show($id);

        if (empty($document["url"])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Deal Jacket Document URL couldn't be found."])->send();
            exit;
        }

        $document["url"] = CloudStorageFactory::getSecureInstance()->getFileUrl($document["url"]);

        $this->response->setContentType('application/json')->setJSON($document)->send();
    }

    public function delete($deal_id, $id)
    {
        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $this->isLocked($deal);

        try {
            $this->repository->delete($id);
            $this->response->setContentType('application/json')->setJSON(["success" => "Deal Jacket Document has been deleted."])->send();
        } catch (\Exception $exception) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "There were some errors in deleting the Deal Jacket Document."])->send();
        }
    }



    public function index($deal_id)
    {
        $deal = $this->secondary_repository->show($deal_id);

        $this->existenceOrRightsCheckForDeal($deal);

        $response = $this->repository->findByDealId($deal_id);

        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Deal Jacket Documents couldn't be found for this deal."])->send();
        }
    }

    public function getTemplate($deal_id){
        $deal = $this->secondary_repository->show($deal_id);
        $this->existenceOrRightsCheckForDeal($deal);
        $deal = $this->deal_controller->_appendDealDependencyResponse($deal_id,$deal);
        $deal_jacket_organizer_template = $this->configuration_repository->findConfigurationColumnsByKeyAsArray('deal_jacket_organizer_template', 'value');
        $deal_jacket_organizer_template = !empty($deal_jacket_organizer_template['value']) ? json_decode($deal_jacket_organizer_template['value'], true) : [];
        $deal_based_template = $this->addTemplateSection([], $deal_jacket_organizer_template, "GENERAL_DOCUMENTS");

        if(!empty($deal['retail_or_wholesale_flag'])) {
            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "WHOLESALE");
            if(!empty($deal['fees'])){
                $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "FEES");
            }

        } else if($deal['type'] === "Financed"){
            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "RETAIL_FINANCE");
            if (!empty($deal['customers'])) {
                foreach ($deal['customers'] as $key => $customer) {
                    if(!empty($customer['deal_customer_type'])) {
                        if($customer['deal_customer_type'] !== "primary_customer") {
                            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "CO_APPLICANT");
                        }
                    }
                }
            }

            $deal_based_template = $this->retailSections($deal_based_template, $deal_jacket_organizer_template, $deal);
        } else {
            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "RETAIL_CASH");
            $deal_based_template = $this->retailSections($deal_based_template, $deal_jacket_organizer_template, $deal);

        }

        $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "PAYMENT");
        $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "MISCELLANEOUS");

        if(!empty($deal_based_template)){
            $this->response->setContentType('application/json')->setJSON($deal_based_template)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Deal Jacket Template could not be found."])->send();
        }
    }

    public function validateTemplate()
    {
        $data = $this->request->getPost();
        $errors = [];


        $validated = $this->validate([
            'deal_jacket_organizer_template' => [
                'label' => 'Deal Jacket Template',
                'rules' => 'required|valid_json',
            ],
        ]);

        if ($validated) {
            $deal_jacket_organizer_template = json_decode($data['deal_jacket_organizer_template'], true);
            foreach ($deal_jacket_organizer_template as $section_key => $section) {
                if (!empty($section['documents'])) {
                    foreach ($section['documents'] as $document_key => $document) {
                        $validation =  \Config\Services::validation();
                        $validation->setRules([
                            'document_type'  => 'required',
                            'label' => 'required',
                            'requirement_type' => 'required',
                            'mapper_id' => 'required',
                            'section_type' => 'required',

                        ]);

                        if (!$validation->run($document)) {
                            if (!empty($validation->getErrors())) {
                                $errors[$section_key][$document_key] = $validation->getErrors();
                            }
                        }
                        $validation->reset();
                    }
                }
            }

            if (empty($errors)) {
                $this->response->setContentType('application/json')->setJSON(['success' => "Organizer template has been validated."])->send();
            } else {
                $errors = json_encode($errors, JSON_FORCE_OBJECT);
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
            }

            exit;
        }

        $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
    }


    /**
     * existenceOrRightsCheckForDeal This function checks if the deal is present or created by 
     *
     * @return void
     */
    protected function existenceOrRightsCheckForDeal($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        if (!service('authPolicy')->hasElevatedRoles()) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "You do not have enough privileges to see the deal."])->send();
            exit;
        }
    }

    /**
     * isLocked This function checks if the deal is locked
     *
     * @return void
     */
    protected function isLocked($deal)
    {
        if ($deal == null || !isset($deal['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal couldn't be found."
            ])->send();
            exit;
        }

        if (intval($deal['lock']) === 1) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Deal is locked."
            ])->send();
            exit;
        }
    }

    private function addTemplateSection(array $current_template, array $complete_template, string $section_type): array
    {
        $matched_template = array_filter($complete_template, function($k) use ($section_type) {
            return $k['section_type'] == $section_type;
        }, ARRAY_FILTER_USE_BOTH);

        if(!empty($matched_template)){
            $current_template = array_merge($current_template, $matched_template);
        }
        return $current_template;
    }

    private function retailSections(array $deal_based_template, array $deal_jacket_organizer_template, $deal): array {
        $deal_based_template = $this->addTradeInSection($deal_based_template, $deal_jacket_organizer_template, $deal);

        if(!empty($deal['fees'])){
        $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "FEES");
        }

        if(!empty($deal['warranties'])){
            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "WARRANTY");
        }

        if(!empty($deal['insurances'])){
            $deal_based_template = $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "INSURANCE");
        }

        return $deal_based_template;
    }

    private function addTradeInSection(array $deal_based_template, array $deal_jacket_organizer_template, $deal): array
    {
        if(!empty($deal['tradein'])){
            return  $this->addTemplateSection($deal_based_template, $deal_jacket_organizer_template, "TRADE_IN");
        }
        return $deal_based_template;
    }


}
