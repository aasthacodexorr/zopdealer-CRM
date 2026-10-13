<?php

namespace App\Libraries\DealerTrack\Import\Lender;

use App\Interfaces\DealerTrackLenderInterface;

/**
 * LenderFactory This method creates the instance of Leader for the lender based varition of the fees.
 */
class LenderFactory
{
    public static function getInstance(String $lender_identifier): DealerTrackLenderInterface
    {
        switch ($lender_identifier) {
            case 'CRV':
                return new CreditRiverCaptialInc();
                break;

            case 'DSJ':
                return new Desjardins();
                break;

            case 'GNB':
                return new GeneralBank();
                break;

            case 'LCR':
                return new LenderCareCapitalInc();
                break;

            case 'BNC':
                return new NationalBankOfCanada();
                break;

            case 'RBC':
                return new RoyalBankOfCanada();
                break;

            case 'CAR':
                return new SantadorConsumerInc();
                break;

            case 'BNS':
                return new ScotiaBank();
                break;

            case 'TDC':
                return new TDAutoFinance();
                break;

            case 'TRI':
                return new TricoreLeaseAndFinancingCorp();
                break;

            case "WDR":
                return new WindsorFamilyCreditUnion();
                break;

            case "ACP":
                return new AutoCapitalCanadaInc();
                break;

            case "EDE":
                return new EdenParkInc();
                break;

            case "CTO":
                return new IAAutoFinanceInc();
                break;

            case "ICE":
                return new IcebergFinance();
                break;

            case "CIB":
                return new CIBCAutoFinance();
                break;

            case "NFY":
                return new NorthLakeFinancial();
                break;

            case "NFZ":
                return new NorthLakeFinancialULC();
                break;

            case "TLC":
                return new ScotiaDealerAdvantage();
                break;

            default:
                return new DefaultLender();
                break;
        }
    }
}
