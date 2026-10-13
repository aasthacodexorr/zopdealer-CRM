<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\VoucherRepository;

set_time_limit(0);

/**
 * VoucherExpiryQueue This method iterates through the vouchers of the clients and update the status
 */
class VoucherExpiryQueue extends Controller
{

    /**
     * repository 
     *
     * @var VoucherRepository
     */
    private $repository;

    private $client_repository;

    public function __construct()
    {
        $this->repository = new VoucherRepository();
        $this->client_repository = new ClientRepository();
    }

    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('voucher_expiry_schedule', 100);

        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {

                $db = \Config\Database::connect();
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
                $this->repository->updateExpiredVoucherStatus();
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
            } finally {

                $db->setDatabase(getenv('database.default.database'));

                $minutes = rand(10, 15) * 10;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);

                $this->updateSchedule($client['client_id'], $scheduled_datetime);
            }
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        try {
            $db = \Config\Database::connect();
            $db->setDatabase(getenv('database.default.database'));
            $builder = $db->table('clients');
            $builder->where('client_id', $client_id);
            $builder->update(['voucher_expiry_schedule' => $scheduled_datetime]);
        } catch (\Exception $ex) {
            log_message('warning', $ex->getMessage());
        }
    }
}
