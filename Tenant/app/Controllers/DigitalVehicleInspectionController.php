<?php

namespace Tenant\app\Controllers;

use Knp\Snappy\Pdf;
use Tenant\app\Libraries\DVI\DigitalVehicleInspectionComposite;
use Tenant\app\Repositories\DigitalVehicleInspectionRepository;
use Tenant\app\Repositories\DigitalVehicleInspectionTemplateRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

class DigitalVehicleInspectionController extends \App\Controllers\BaseController
{
    /**
     *
     * @var DigitalVehicleInspectionTemplateRepository
     */
    private $dvi_template_repository;

    private $dvi_composite;

    /**
     * repository
     *
     * @var DigitalVehicleInspectionRepository
     */
    private $repository;

    public function __construct()
    {
        $this->dvi_template_repository = new DigitalVehicleInspectionTemplateRepository();
        $this->dvi_composite = new DigitalVehicleInspectionComposite();
        $this->repository = new DigitalVehicleInspectionRepository();
    }

    public function index()
    {

        $query = [];
        $query = null;
        if (count($_GET) > 0) {
            $query = $this->repository->prepareDataForFilter($_GET);
        }

        $items = 100;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->repository;

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, $query, $items, $sort, $paginate, 'digital_vehicle_inspection_view_id', 'digital_vehicle_inspection_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function clone()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'stock_no' => [
                'label' => 'Stock Number',
                'rules' => 'required',
            ],
            'template_id' => [
                'label' => 'Template ID',
                'rules' => 'required',
            ],
        ]);

        if ($validated) {
            $response = $this->dvi_composite->clone($data['template_id'], $data['stock_no'], service('authPolicy')->id);
            if (isset($response['success'])) {

                if (!empty($response['digital_vehicle_inspection_id'])) {
                    $this->repository->updateSearchIndex([$response['digital_vehicle_inspection_id']]);
                }

                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function render($id)
    {
        $response = $this->dvi_composite->render($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI couldn't be found."])->send();
        }
    }

    public function partialUpdate()
    {
        $data = $this->request->getPost();

        $validated = $this->validate([
            'digital_vehicle_inspection_id' => [
                'label' => 'DVI ID',
                'rules' => 'required',
            ],
            'column' => [
                'label' => 'Column',
                'rules' => 'required',
            ],
            'value' => [
                'label' => 'Value',
                'rules' => 'permit_empty',
            ],
        ]);

        if ($validated) {
            $dvi = [];
            $dvi[$data['column']] = $data['value'];
            $response = $this->dvi_composite->partialUpdate($data['digital_vehicle_inspection_id'], $dvi);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $errors = $this->validator->getErrors();
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function generatePDF($id)
    {
        $response = $this->dvi_composite->render($id);
        $title = 'DVI_';
        if (!empty($response)) {
            $title .= preg_replace('/[^a-zA-Z0-9]+/', '_', $response['title']);
            $title = substr($title, 0, 50);

            if (is_file(APPPATH . 'Views/templates/dvi.php')) {
                if (!empty($response['assignee'])) {
                    $assignee = (new TenantUserMetaDataRepository())->getUserMetaDataById($response['assignee']);
                    if (!empty($assignee[0]['name'])) {
                        $response['assignee'] = $assignee[0]['name'];
                    }
                }

                if (!empty($response['stock_no']) && !empty($response['inventory'])) {
                    $title .= '_' . preg_replace('/[^a-zA-Z0-9]+/', '_', $response['stock_no']);
                }

                $response['pdf_title'] = $title;

                $snappy = new Pdf();
                $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
                $snappy->setBinary($path);
                $template = view('Views/templates/dvi', $response);
                $this->response->setHeader('Content-Type', 'application/pdf');
                $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $title . '.pdf"');
                echo $snappy->getOutputFromHtml($template);
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Template couldn't be found."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI couldn't be found."])->send();
        }
    }

    public function showItem($id, $item_id)
    {
        $response = $this->dvi_composite->fetchItem($id, $item_id);
        if (!isset($response) || !empty($response['errors'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "DVI Item couldn't be found."])->send();
        } else {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        }
    }

    public function updateItem($id, $item_id)
    {
        $data = $this->request->getPost();
        $response = $this->dvi_composite->updateItem($id, $item_id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function saveItemAttachment($id, $item_id)
    {
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'ext_in[file,png,jpg,jpeg,heif,heic]',
                'max_size[file,131072]',
            ],
        ]);

        if ($validated) {
            $response = $this->dvi_composite->saveItemAttachment($id, $item_id, $this->request->getFile('file'));
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function deleteItemAttachment($id, $item_id, $attachment_id)
    {
        $response = $this->dvi_composite->deleteItemAttachment($id, $item_id, $attachment_id);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }
}
