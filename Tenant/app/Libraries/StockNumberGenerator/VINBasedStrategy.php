<?php

namespace Tenant\app\Libraries\StockNumberGenerator;

final class VINBasedStrategy extends AbstractStockNumberGenerator implements StockNumberGeneratorInterface
{

    public function generate(int $len = 6, String $prefix = '', int $tries = 10, array $options = []): String
    {
        if (empty($options['vin']) || $tries === 0) {
            return parent::generate($len, $prefix, $tries - 1,  $options);
        }

        $stock_no = substr($options['vin'], -$len);
        $stock_no = $prefix . $stock_no;

        if ($this->doesStockNumberExist($stock_no)) {
            return parent::generate($len, $prefix, $tries - 1,  $options);
        }

        return $stock_no;
    }
}
