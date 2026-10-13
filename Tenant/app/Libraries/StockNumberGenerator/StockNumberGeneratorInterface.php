<?php

namespace Tenant\app\Libraries\StockNumberGenerator;

/***
 * StockNumberGeneratorInterface defines the method that can be
 */
interface StockNumberGeneratorInterface
{
    /**
     * generate To generate and return a stock number
     *
     * @param  int $len
     * @param  String $prefix
     * @param  int $tries
     * @param  array $options
     * @return String
     */
    public function generate(int $len = 6, String $prefix = '', int $tries = 10, array $options = []): String;

    /**
     * doesStockNumberExist
     *
     * @param  String $stock_no
     * @return bool
     */
    public function doesStockNumberExist(string $stock_no): bool;
}
