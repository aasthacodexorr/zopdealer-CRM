<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Repositories\AdvanceRepository;
use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\VendorModel;
use Tenant\app\Entities\Vendor;
use Carbon\Carbon;
use Tenant\app\Models\SMSConversationModel;

/**
 * SMSConversationRepository CRUD and pagination for the SMS Conversation
 */
class SMSConversationRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new SMSConversationModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'SMS';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'sms_id';
    }

    /**
     * salesperson_number
     *
     * @var String
     */
    private $salesperson_number;


    /**
     * findBySID
     *
     * @param  String $sid
     * @return void
     */
    public function findBySID(String $sid): ?array
    {
        return $this->model->where('sid', $sid)->asArray()->first();
    }

    /**
     * getConversation
     *
     * @param  mixed $phone_no_1
     * @param  mixed $phone_no_2
     * @return void
     */
    public function getConversation(String $phone_no_1, String $phone_no_2)
    {

        $conditions = [
            [
                'header' => null,
                'column' => 'from',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Multi Select",
                'value_1' => [$phone_no_1, $phone_no_2],
                'value_2' => null
            ],
            [
                'header' => null,
                'column' => 'to',
                'join' => 'AND',
                'comparator' => "is",
                "type" => "Multi Select",
                'value_1' => [$phone_no_1, $phone_no_2],
                'value_2' => null
            ]
        ];

        return parent::index(null, null, null, json_encode($conditions), 20, $sort = '{"created_at" : "ASC"}', false, 'view_id', 'sms_conversation_list');
    }

    /**
     * updateRead Updates the Read Flag between Phone 1 and Phone 2.
     *
     * @param  mixed $phone_no_1
     * @param  mixed $phone_no_2
     * @return void
     */
    public function updateRead(String $phone_no_1, String $phone_no_2)
    {
        $this->model->where('from', $phone_no_1)->where('to', $phone_no_2)->set(['read' => 1])->update();
        $this->model->where('to', $phone_no_1)->where('from', $phone_no_2)->set(['read' => 1])->update();
        return ['success' => 'Read status updated.'];
    }

    /**
     * getUnreadCount
     *
     * @param  mixed $phone_no_1
     * @return void
     */
    public function getUnreadCount(String $phone_no_1)
    {
        return $this->model->where('read', 0)->where('to', $phone_no_1)->countAllResults();
    }

    public function index($id = null, $header = null, $view = null, $query = null, $items = 20, $sort = null, $paginate = true, $view_id_title = 'view_id', $data_list = 'data_list')
    {


        $this->allQueries($query);

        // $sql = $this->model->getCompiledSelect();
        // echo $sql;
        // exit;

        $sorting_order = $sort != null ? json_decode($sort, true) : json_decode('{"created_at" : "DESC"}', true);

        foreach ($sorting_order as $sort_key => $sort_value) {
            $this->model->orderBy($sort_key, $sort_value);
        }

        if (!empty($this->salesperson_number)) {
            $this->model->having('salesperson_number', $this->salesperson_number);
        }
        if ($paginate) {
            return [
                $view_id_title => $id,
                'header' => $header,
                $data_list => $this->model->asArray()->paginate($items),
                'pager' => $this->model->pager->getDetails(),
                'view' => json_decode($view),
                'sort' => $sorting_order,
            ];
        } else {
            return [
                $data_list => $this->model->asArray()->findAll()
            ];
        }
    }

    /**
     * Set salesperson_number
     *
     * @param  String  $salesperson_number  salesperson_number
     *
     * @return  self
     */
    public function setSalesperson_number(String $salesperson_number)
    {
        $this->salesperson_number = $salesperson_number;

        return $this;
    }
}
