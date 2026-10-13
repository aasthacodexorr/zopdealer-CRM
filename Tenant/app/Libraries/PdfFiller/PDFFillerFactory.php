<?php

namespace Tenant\app\Libraries\PdfFiller;

use Tenant\app\Libraries\PdfFiller\Version1\BillOfSaleRetail as BillOfSaleRetailV1;
use Tenant\app\Libraries\PdfFiller\Version1\BillOfSaleWholesale as BoSWholesaleV1;
use Tenant\app\Libraries\PdfFiller\Version1\BuyersGuide;
use Tenant\app\Libraries\PdfFiller\Version1\CreditApplication;
use Tenant\app\Libraries\PdfFiller\Version1\PublicPurchaseAgreement;
use Tenant\app\Libraries\PdfFiller\Version1\TestDrive;
use Tenant\app\Libraries\PdfFiller\Version1\VehicleHistoryReportAcknowledgement;
use Tenant\app\Libraries\PdfFiller\Version1\CustomerWorksheet;
use Tenant\app\Libraries\PdfFiller\Version1\DealRecap;
use Tenant\app\Libraries\PdfFiller\Version1\InternalInspectionChecklist;
use Tenant\app\Libraries\PdfFiller\Version1\OpenDisclosureAgreement;
use Tenant\app\Libraries\PdfFiller\Version1\PaymentReceipt;
use Tenant\app\Libraries\PdfFiller\Version1\TradeIn;
use Tenant\app\Libraries\PdfFiller\Version1\VehicleDisclosures;
use Tenant\app\Libraries\PdfFiller\PDFFillerConstant;


/**
 * PDFFillerFactory This should be called for fetching the PDFFiller
 */
class PDFFillerFactory
{
    public static function getInstance(String $template, int $type): ?PDFFillerInterface
    {
        try {
            switch ($type) {
                case PDFFillerConstant::BOS_RETAIL:
                    return new BillOfSaleRetailV1(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::BOS_WHOLESALE:
                    return new BoSWholesaleV1(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;


                case PDFFillerConstant::TEST_DRIVE:
                    return new TestDrive(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::CREDIT_APPLICATION:
                    return new CreditApplication(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::PUBLIC_PURCHASE_AGREEMENT:
                    return new PublicPurchaseAgreement(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::VEHICLE_HISTORY_REPORT_ACKNOWLEDGEMENT:
                    return new VehicleHistoryReportAcknowledgement(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::CUSTOMER_WORKSHEET:
                    return new CustomerWorksheet(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::OPEN_DISCLOSURE_AGREEMENT:
                    return new OpenDisclosureAgreement(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::VEHICLE_DISCLOSURES:
                    return new VehicleDisclosures(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::TRADEIN:
                    return new TradeIn(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::DEAL_RECAP:
                    return new DealRecap(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::INTERNAL_INSPECTION_CHECKLIST:
                    return new InternalInspectionChecklist(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::BUYERS_GUIDE:
                    return new BuyersGuide(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;

                case PDFFillerConstant::PAYMENT_RECEIPT:
                    return new PaymentReceipt(ROOTPATH . "/public/media/pdf_template/" . $template);
                    break;


                default:
                    throw new \Exception("PDF Template Could Not Be Found.", 1);
                    break;
            }
        } catch (\Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return null;
    }
}
