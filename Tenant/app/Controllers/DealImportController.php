<?php

namespace Tenant\app\Controllers;

use App\Libraries\CloudStorage\CloudStorageFactory;
use Error;
use Exception;
use Tenant\app\Libraries\Import\DealImport;
use Tenant\app\Libraries\PDFParser\PDFParserFactory;
use Tenant\app\Libraries\WebExtensionTransformer\DealerTrack as DealerTrackTransformer;
use Tenant\app\Repositories\ConfigurationRepository;

class DealImportController extends \App\Controllers\BaseController
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    protected $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function completeDealEntity()
    {
        $namespaces = [
            'Tenant\app\Libraries\Import\DealBaseBreakdown',
            'Tenant\app\Libraries\Import\DealFinance',
            'Tenant\app\Libraries\Import\DealFee',
            'Tenant\app\Libraries\Import\DealAccessory',
            'Tenant\app\Libraries\Import\DealWarranty',
            'Tenant\app\Libraries\Import\DealGuarantee',
            'Tenant\app\Libraries\Import\DealInsurance',
            'Tenant\app\Libraries\Import\DealPayment',
            'Tenant\app\Libraries\Import\DealTradeIn',
            'Tenant\app\Libraries\Import\DealInventory',
            'Tenant\app\Libraries\Import\DealCustomer'
        ];

        $data = $this->request->getPost();


        $dealImport = new DealImport();
        $errors = $dealImport->validate($data, $namespaces, []);

        if (empty($errors)) {
            $response = $dealImport->process($data, $namespaces, []);

            if (!empty($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($errors)->send();
        }
    }

    public function parsePDF($source)
    {
        $validated = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,application/pdf]',
                'max_size[file,15000]',
            ],
        ]);

        if ($validated) {

            $file_tmp  = $_FILES['file']['tmp_name'];
            $cFile = curl_file_create($file_tmp);
            $success = false;
            $pdfParser = PDFParserFactory::getInstance($source);

            $response = [];

            if ($pdfParser !== null) {
                $pdf_parser_response = $pdfParser->sendRequest($cFile);
                if (!empty($pdf_parser_response['status'])) {
                    if ($pdf_parser_response['status']  === 'success') {
                        $success = true;
                        $response = $pdfParser->mapResponse($pdf_parser_response);
                    }
                }
            }
            if ($success) {
                $file = $this->request->getFile('file');
                $name = $file->getRandomName();
                CloudStorageFactory::getInstance()->save($file, ROOT_FILE . '/' . md5(db_connect()->database) . '/' . $name);
                $response['deal_import_url'] = "/" . ROOT_FILE . "/" . md5(db_connect()->database) . "/" . $name;
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "We were unable to parse the PDF."])->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }
}
