<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use Tenant\app\Entities\LeadResponseView;
use Tenant\app\Models\LeadReportModel;
use Tenant\app\Models\LeadReportViewModel;

/**
 * LeadReportViewRepository CRUD and complex queries for LeadReportView Model
 */
class LeadReportViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new LeadReportViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }


    /**
     * show This shows single instance of entity
     *
     * @param  mixed $id
     * @return array
     */
    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * creates new Lead Report View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new LeadResponseView($data)) === false) {
            return $this->model->errors();
        } else {
            $lead_view_id = $this->model->insertID;
            return [
                "success" => 'Lead Report View Created',
                "id" => $lead_view_id
            ];
        }
    }

    /**
     * update Updates the Lead Report View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $leadView = new LeadResponseView($data);
        $leadView->id = $id;

        if ($this->model->save($leadView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Lead Report View has been updated.',
            ];
        }
    }

    public function result($id, $pagination = true)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $leadRepository = new LeadResponseRepository();
        $leadRepository->setModel(new LeadReportModel());

        $iv =  $this->model->find($id);
        return $leadRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, $pagination, 'lead_view_id', 'lead_listing');
    }

    /**
     * @method prepareDataForFilter : prepares data recieved.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_stock_no' || $key == 'zm_source'     
                    || $key == 'zm_status' || $key == 'zm_assignee'

                ) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                }
            }


            if(!empty($data['zm_date_start']) && !empty($data['zm_date_end'])){
                $single_query = [
                    'header' => null,
                    'column' => "created_at",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }
}
