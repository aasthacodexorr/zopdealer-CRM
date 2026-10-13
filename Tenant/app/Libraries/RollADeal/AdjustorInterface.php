<?php

namespace Tenant\app\Libraries\RollADeal;

use Tenant\App\Entities\DealWorksheet;

/***
 * AdjustorInterface defines the structure of the Adjustor's of Deal.
 */
interface AdjustorInterface
{
    /**
     * adjust This method accept's DealWorksheet and returns an updated Worksheet
     *
     * @param  DealWorksheet $worksheet
     * @return DealWorksheet
     */
    public function adjust(DealWorksheet $worksheet): float;
}
