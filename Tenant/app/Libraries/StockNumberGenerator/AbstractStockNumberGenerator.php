<?php

namespace Tenant\app\Libraries\StockNumberGenerator;

use App\Helpers\Utility;
use Tenant\app\Libraries\StockNumberGenerator\StockNumberGeneratorInterface;
use Tenant\app\Models\InventoryWithDeletedModel;
use Tenant\app\Repositories\InventoryRepository;

abstract class AbstractStockNumberGenerator implements StockNumberGeneratorInterface
{
    public function generate(int $len = 6, String $prefix = '', int $tries = 10, array $options = []): String
    {
        $stock_no = $prefix  . strtoupper(Utility::randomString($len));

        if ($this->doesStockNumberExist($stock_no)) {
            return $this->generate($len, $prefix, $tries, $options);
        }

        return $stock_no;
    }

    public function doesStockNumberExist(string $stock_no): bool
    {
        $inventory_repository = (new InventoryRepository());
        $inventory_repository->setModel(new InventoryWithDeletedModel());
        $inventory = $inventory_repository->findSingleInventoryByStockId($stock_no);

        if ($inventory) {
            return true;
        }

        return false;
    }
}
