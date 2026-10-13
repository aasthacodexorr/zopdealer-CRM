<?php

namespace Tenant\app\Models;

class DealReportModelWithDeleted extends DealReportModel
{
    protected $useSoftDeletes = false;
}
