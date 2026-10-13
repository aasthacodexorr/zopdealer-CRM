<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Repositories\AdvanceRepository;
use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Models\ActivityModel;
use Tenant\app\Models\PaymentModel;
use Tenant\app\Models\StripePaymentModel;

/**
 * StripePaymentRepository CRUD and pagination for the StripePayment
 */
class StripePaymentRepository extends PaymentRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->setModel(new StripePaymentModel());
    }
}