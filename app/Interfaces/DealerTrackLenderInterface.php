<?php

namespace App\Interfaces;

/**
 * DealerTrackLenderInterface This class gives the structure for the classes for the Lender Worksheet.
 */
interface DealerTrackLenderInterface
{
    /**
     * getTaxableFees
     *
     * @return array
     */
    public function getTaxableFees(): array;

    /**
     * getNonTaxableFees
     *
     * @return array
     */
    public function getNonTaxableFees(): array;

    /**
     * getTaxableWarranties
     *
     * @return array
     */
    public function getTaxableWarranties(): array;


    /**
     * addTaxableWarranties
     *
     * @param  String $fee
     * @return void
     */
    public function addTaxableWarranties(String $fee): void;


    /**
     * getNonTaxableWarranties
     *
     * @return array
     */
    public function getNonTaxableWarranties(): array;


    /**
     * addNonTaxableWarranties
     *
     * @param  mixed $fee
     * @return void
     */
    public function addNonTaxableWarranties(String $fee): void;

    /**
     * getWarrantiesAsFees
     *
     * @return array
     */
    public function getWarrantiesAsFees(): array;

    /**
     * getTaxableInsurance
     *
     * @return array
     */
    public function getTaxableInsurance(): array;


    /**
     * addTaxableInsurance
     *
     * @param  mixed $fee
     * @return void
     */
    public function addTaxableInsurance(String $fee): void;

    /**
     * hasLicenceFee
     *
     * @return bool
     */
    public function hasLicenceFee(): bool;

    /**
     * hasOtherAfterMarketFee
     *
     * @return bool
     */
    public function hasOtherAfterMarketFee(): bool;

    /**
     * hasOtherInsurance
     *
     * @return bool
     */
    public function hasOtherInsurance(): bool;
}
