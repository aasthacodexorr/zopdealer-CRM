<?php

namespace Tenant\app\Libraries\PdfFiller\Version1;

use App\Helpers\Utility;
use Tenant\app\Libraries\PdfFiller\PDFFillerInterface;
use mikehaertl\pdftk\Pdf;

class InternalInspectionChecklist extends BillOfSaleRetail implements PDFFillerInterface
{
    public function __construct(String $template_name)
    {
        $this->template_name = $template_name;
        $this->pdf_driver = new Pdf($template_name);
        $this->company_as_name = false;
    }

    public function dataMap(array $data)
    {
        parent::dataMap($data);

        if (!empty($data['inventory_inspection'])) {
            foreach ($data['inventory_inspection'] as $inventory_inspection) {

                switch ($inventory_inspection['type']) {

                    case 'Switch':

                        $this->data['zd_internal_inspection_' . Utility::slugify($inventory_inspection['question'], '_')] = $inventory_inspection['answer'];
                        if ($this->printAdditionalInformationForPostiveAnswer($inventory_inspection)) {
                            $this->data['zd_internal_inspection_' . Utility::slugify($inventory_inspection['question'], '_') . "_additional_information"] = $inventory_inspection['additional_information'];
                        }
                        break;

                    case 'Textbox':
                    case 'InputField':
                        $this->data['zd_internal_inspection_' . Utility::slugify($inventory_inspection['question'], '_')] = $inventory_inspection['answer'];
                        break;
                    default:
                        break;
                }
            }
        }

        return $this;
    }

    protected function printAdditionalInformationForPostiveAnswer(array $inspection_point): bool
    {
        $has_additional_information = $inspection_point['has_additional_information'] == "1" ? true : false;
        $positive_answer_list = ["Yes", "Pass", "Passed"];
        return $has_additional_information && in_array($inspection_point['answer'], $positive_answer_list);
    }
}
