<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Models\VoucherTemplateModel;

/**
 * VoucherTemplateRepository CRUD and pagination for the Voucher Template
 */
class VoucherTemplateRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new VoucherTemplateModel());
        $this->entity = 'Tenant\app\Entities\VoucherTemplate';
        $this->entityLabel = 'Voucher Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'voucher_template_id';
    }

    public function calculateRelativeDateFromToday(string $interval, string $unit): string
    {
        $current_datetime = Carbon::now();

        switch ($unit) {
            case 'DAYS':
                $current_datetime->addDays($interval);
                break;

            case 'WEEKS':
                $current_datetime->addWeeks($interval);
                break;

            case 'MONTHS':
                $current_datetime->addMonths($interval);
                break;

            case 'YEARS':
                $current_datetime->addYears($interval);
                break;

            default:
                # code...
                break;
        }

        return $current_datetime->toDateString();
    }
}
