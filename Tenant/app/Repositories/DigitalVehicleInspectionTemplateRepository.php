<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DigitalVehicleInspectionTemplateModel;
use Tenant\app\Models\DigitalVehicleInspectionTemplatePivotModel;

class DigitalVehicleInspectionTemplateRepository extends CRUDWithAdvanceRepository
{
    /**
     * dvi_template_pivot_table
     *
     * @var DigitalVehicleInspectionTemplatePivotModel
     */
    private $dvi_template_pivot_table;

    private $digital_vehicle_inspection_template_item_repository;

    public function __construct()
    {
        parent::__construct(new DigitalVehicleInspectionTemplateModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'DVI Template';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'digital_vehicle_inspection_template_id';
        $this->dvi_template_pivot_table = new DigitalVehicleInspectionTemplatePivotModel();
        $this->digital_vehicle_inspection_template_item_repository = new DigitalVehicleInspectionTemplateItemRepository();
    }

    public function show($id): ?array
    {

        $entity = $this->model->asArray()->find($id);
        $items = [];
        if (!empty($entity)) {
            $template_item_pivot = $this->dvi_template_pivot_table->where('dvi_template_id', $id)->orderBy('sort_rank', 'ASC')->asArray()->findAll();
            $item_ids = array_map(function ($item) {
                return $item['dvi_template_item_id'];
            }, $template_item_pivot);

            if (!empty($item_ids)) {
                $items = $this->digital_vehicle_inspection_template_item_repository->getModel()->whereIn('id', $item_ids)->orderBy('FIELD(id, ' . implode(',', $item_ids) . ')')->asArray()->findAll();
            }
        }
        $entity['items'] = $items;

        return $entity;
    }

    public function create($data): array
    {
        $response = parent::create($data);
        if (!empty($response[$this->id_field])) {
            $this->postProcessTemplateItems($response[$this->id_field], $data);
        }

        return $response;
    }

    public function update($id, $data): array
    {
        $response = parent::update($id, $data);
        if (isset($response['success'])) {
            $this->postProcessTemplateItems($id, $data);
        }

        return $response;
    }

    private function postProcessTemplateItems($template_id, $data): void
    {
        if (!empty($data['items'])) {
            $validation =  \Config\Services::validation();
            $validation->setRules(['items' => ['rule' => 'validate_integer_json_array']]);
            if ($validation->run($data)) {
                $items = json_decode($data['items'], true);
                $template_item_entities = array_map(function ($item) use ($template_id) {
                    static $item_entity_index = 1;
                    $template_item = [
                        'dvi_template_id' => $template_id,
                        'dvi_template_item_id' => $item,
                        'sort_rank' => $item_entity_index
                    ];
                    $item_entity_index++;
                    return $template_item;
                }, $items);

                if (!empty($template_item_entities)) {
                    $this->dvi_template_pivot_table->where('dvi_template_id', $template_id)->delete();
                    $this->dvi_template_pivot_table->insertBatch($template_item_entities, true);
                }
            }
        }
    }
}
