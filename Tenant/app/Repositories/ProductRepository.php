<?php

namespace Tenant\app\Repositories;

use App\Helpers\Utility;
use App\Repositories\AdvanceRepository;
use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Models\ProductModel;

/**
 * ProductRepository CRUD and pagination for the Product
 */
class ProductRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new ProductModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Product';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'product_id';
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
                if (false) {
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
                } else if ($key == 'zm_type' || $key == 'zm_category') {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Single Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];
                    array_push($query, $single_query);
                }
            }
        }

        return json_encode($query);
    }

    /**
     * updateSearchIndex
     *
     * @param  mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {

        try {

            $db = \Config\Database::connect();
            $builder = $db->table('products');

            $all_products = NULL;

            parent::setModel(new ProductModel());

            if (empty($ids)) {
                $all_products =  $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_products = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_products as $key => $value) {
                try {
                    $data['data'] = $value;
                    $product_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])->set(['search_keyword' => $product_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the products index.'];
        }

        return ['success' => 'Product search index has been updated.'];
    }
}
