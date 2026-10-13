<?php

namespace Tenant\app\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Controller;
use Tenant\app\Repositories\DealReportRepository;

set_time_limit(0);

/**
 * UpdateDealReportQueue This method iterates through clients for update the reports for deal module.
 */
class UpdateDealReportQueue extends Controller
{

    private $client_repository;

    public function __construct()
    {
        $this->client_repository = new ClientRepository();
    }


    public function process()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('update_deal_report_schedule', 150);
        $db = \Config\Database::connect();
        $clients = $clients['data_list'];
        $current_processing = 0;

        foreach ($clients as $client) {
            try {

                if ($current_processing > 10) {
                    continue;
                }

                $minutes = rand(10, 15);
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);
                $this->updateSchedule($client['client_id'], $scheduled_datetime);
                $client_database = $client['subdomain'] . '_' . $client['client_hash'];
                $db->setDatabase($client_database);

                $report_has_updates = (new DealReportRepository())->hasUpdates();

                if ($report_has_updates) {
                    CLI::write($client_database);
                    ++$current_processing;
                    exec("php index.php updateDealReportQueue processSingleClient $client_database > /dev/null 2>&1 &");
                    sleep(10);
                }
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $this->updateSchedule($client['client_id']);
            }
        }
    }

    public function processSingleClient(...$arg): void
    {
        if (!empty($arg[0])) {
            $db = \Config\Database::connect();
            $db->setDatabase($arg[0]);
            (new DealReportRepository())->updateReportEntities();
        }
    }

    private function updateSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['update_deal_report_schedule' => $scheduled_datetime]);
    }

    public function resetUpdateStatus()
    {
        $clients = $this->client_repository->scheduleQueueBasedOnColumn('reset_deal_status_schedule', 100);
        $db = \Config\Database::connect();

        $clients = $clients['data_list'];

        foreach ($clients as $client) {
            try {
                $db->setDatabase($client['subdomain'] . '_' . $client['client_hash']);
                $builder = $db->table('deals');
                $builder->update(['report_status' => 'TO BE UPDATED']);
                $db->setDatabase(getenv('database.default.database'));
                $minutes = rand(12, 15) * 60;
                $current = Carbon::now('UTC');
                $scheduled_datetime = $current->copy()->addMinutes($minutes);

                $this->resetDealStatusSchedule($client['client_id'], $scheduled_datetime);
            } catch (\Exception $ex) {
                CLI::write("Exception was thrown : " . $ex->getMessage());
                $this->resetDealStatusSchedule($client['client_id']);
            }
        }
    }

    private function resetDealStatusSchedule($client_id, $scheduled_datetime = NULL): void
    {
        $db = \Config\Database::connect();
        $db->setDatabase(getenv('database.default.database'));
        $builder = $db->table('clients');
        $builder->where('client_id', $client_id);
        $builder->update(['reset_deal_status_schedule' => $scheduled_datetime]);
    }
}
