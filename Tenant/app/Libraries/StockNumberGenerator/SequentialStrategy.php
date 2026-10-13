<?php

namespace Tenant\app\Libraries\StockNumberGenerator;

use Tenant\app\Repositories\ConfigurationRepository;

final class SequentialStrategy extends AbstractStockNumberGenerator implements StockNumberGeneratorInterface
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    private $configuration_repository;

    public function __construct()
    {
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function generate(int $len = 6, String $prefix = '', int $tries = 10, array $options = []): String
    {
        if ($tries === 0) {
            return parent::generate($len, $prefix, $tries - 1,  $options);
        }

        $current_inventory_stock_number_index = $this->configuration_repository->findConfigurationColumnsByKey('current_inventory_stock_number_index', ['value']);
        $current_inventory_stock_number_index = $current_inventory_stock_number_index->value;

        $current_index = (int) $current_inventory_stock_number_index;

        $stock_no = $prefix . str_pad($current_index, $len, "0", STR_PAD_LEFT);

        if ($this->doesStockNumberExist($stock_no)) {
            $this->configuration_repository->setConfiguration(["current_inventory_stock_number_index" => $current_index + 1]);
            return $this->generate($len, $prefix, $tries - 1, $options);
        }

        return $stock_no;
    }
}
