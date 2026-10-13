<?php

namespace Tenant\app\Libraries\Report;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

abstract class ReportBuilderAbstract
{

    protected Spreadsheet $spreadsheet;

    protected array $component = [];

    /**
     *  corresponds to table data attribute names
     */
    protected array $headers = [];

    /**
     *  corresponds to the title in the generated sheet
     */
    protected array $titles = [];

    protected array $data = [];

    protected array $view = [];

    protected array $entities = [];

    protected function setReportLayout(): void
    {
        foreach ($this->view as $each_header) {
            if ($each_header->visibility == '1') {
                array_push($this->headers, $each_header->column);
                array_push($this->titles, $each_header->header);
                $this->component[$each_header->column] = $each_header->component;
            }
        }
    }

    protected function prepareDataForReport(): void
    {
        $users_object = (new TenantUserMetaDataRepository())->findAllUsers(["user_metadata.user_id", "user_metadata.name"]);

        $users_id_name_mapping = [];

        foreach ($users_object as $key => $single_user_object) {
            $users_id_name_mapping[$single_user_object->user_id] = $single_user_object->name;
        }

        foreach ($this->entities as $key => $value) {
            array_push($this->data, []);
            foreach ($this->headers as $required_data_key) {

                $cell = $this->entities[$key][$required_data_key];

                switch ($this->component[$required_data_key]) {
                    case 'rtl_wh_flag':
                        $cell = $this->entities[$key][$required_data_key] == "0" ? "Retail" : "Wholesale";
                        break;

                    case 'user':
                        $cell = isset($users_id_name_mapping[$this->entities[$key][$required_data_key]]) ? $users_id_name_mapping[$this->entities[$key][$required_data_key]] : "Not Assigned";
                        break;

                    case 'yes_or_no':
                        $cell = $this->entities[$key][$required_data_key] == "1" ? "YES" : "NO";
                        break;

                    case 'yes_no_unselected':
                        $current_value = $this->entities[$key][$required_data_key];
                        switch ($current_value) {
                            case '1':
                                $cell = "YES";
                                break;
                            case '2':
                                $cell = "NO";
                                break;

                            default:
                                $cell = "";
                                break;
                        }
                        break;

                    default:
                        break;
                }

                switch ($required_data_key) {
                    case 'visibility':
                        $cell = $this->entities[$key][$required_data_key] == "0" ? "OFF" : "ON";
                        break;

                    case 'odometer':
                        $cell = !empty($this->entities[$key][$required_data_key]) ?  $this->entities[$key][$required_data_key] . " " . $this->entities[$key]['km_miles'] : "";
                        break;

                    case 'feature_listing':
                        $cell = $this->entities[$key][$required_data_key] == "0" ? "NO" : "YES";
                        break;

                    default:
                        break;
                }

                $this->data[$key][$required_data_key] = $cell;
            }
        }
    }

    public function output(string $filename = 'php://output', $format = "CSV"): void
    {
        try {
            //Late initialization for the spreadsheet
            $this->setReportLayout();
            $this->prepareDataForReport();

            $this->spreadsheet = new Spreadsheet();
            $this->spreadsheet->getProperties()->setTitle("Sheet");
            $sheet = $this->spreadsheet->getActiveSheet();
            $sheet->fromArray($this->titles);
            $sheet->fromArray($this->data, "", "A2");
            $driver = ReportConstant::CSV;

            switch ($format) {
                case 'XLS':
                    $driver = ReportConstant::XLS;
                    break;

                case 'PDF':
                    $driver = ReportConstant::PDF;
                    break;
            }
            $writer = IOFactory::createWriter($this->spreadsheet, $driver);
            $writer->save($filename);
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
        }
    }

    public function getContentTypeHeaderBasedOnReportType(string $report_type = "CSV"): string
    {
        switch (strtoupper($report_type)) {
            case 'PDF':
                return "application/pdf";

            case 'XLS':
                return "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

            default:
                return "text/csv";
        }
    }
}
