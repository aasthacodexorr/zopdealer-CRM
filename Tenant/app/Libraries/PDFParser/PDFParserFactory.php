<?php

namespace Tenant\app\Libraries\PDFParser;

use Tenant\app\Libraries\PDFParser\PDFParserInterface;

/**
 * PDFParserFactory This should be called for fetching the PDFParser
 */
class PDFParserFactory
{
    public static function getInstance(String $source): ?PDFParserInterface
    {
        switch ($source) {
            case 'eblock':
                return new EblockPDFParser();
                break;

            case 'openlane':
                return new OpenlanePDFParser();
                break;

            case 'adesa':
                return new AdesaPDFParser();
                break;

            case 'traderev':
                return new TradeRevPDFParser();
                break;

            case 'iaa':
                return new IAAPDFParser();
                break;

            case 'otolane':
                return new OtolanePDFParser();
                break;

            case 'dealertrack':
                return new DealertrackPDFParser();
                break;

            default:
                return null;
                break;
        }

        return null;
    }
}
