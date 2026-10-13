<?php

namespace Tenant\app\Libraries\StockNumberGenerator;

use Exception;

class StockNumberGeneratorFactory
{
    private int $length = 1;

    public static function getInstance(String $type): StockNumberGeneratorInterface
    {


        try {
            switch ($type) {
                case StockNumberGeneratorConstant::RANDOM:
                    return new RandomStrategy();
                    break;

                case StockNumberGeneratorConstant::VINBASED;
                    return new VINBasedStrategy();
                    break;

                case StockNumberGeneratorConstant::SEQUENTIAL;
                    return new SequentialStrategy();
                    break;

                default:
                    return new RandomStrategy();
                    break;
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
            return new RandomStrategy();
        }
    }
}
