<?php

namespace App\Repositories;

use App\Entities\GlobalSyndicationCompany;
use App\Models\GlobalSyndicationCompanyModel;


/**
 * GlobalSyndicationCompanyRepository Repository for managing the global syndication companies.
 */
class GlobalSyndicationCompanyRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new GlobalSyndicationCompanyModel());
    }

    /**
     * create Create entry for new syndication company
     *
     * @param  array $data This represents the data needed to create new entry of global syndication company.
     * @return array Response sends either error or success.
     */
    public function create(array $data): array
    {
        $globalSyndicationCompany = new GlobalSyndicationCompany($data);

        if ($this->model->save($globalSyndicationCompany) === false) {
            return $this->model->errors();
        } else {

            $global_syndication_company_id = $this->model->insertID;
            return [
                'success' => 'New Global Syndication company has been added.',
                'global_syndication_company_id' => $global_syndication_company_id,
            ];
        }
    }

    /**
     * index List of Syndication Companies
     *
     * @return array Returns the array of data along with pagniation.
     */
    public function index($paginate = true, $items = 25): array
    {

        $this->model->orderBy('id', 'DESC');

        $data = null;

        if ($paginate) {

            $data = [
                'syndication_companies_list' => $this->model->asObject()->paginate($items),
                'pager' => $this->model->pager->getDetails(),
            ];

        } else {
            $data = [
                'syndication_companies_list' => $this->model->asObject()->findAll()
            ];
        }
        return $data;
    }
}
