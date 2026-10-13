<?php

namespace Tenant\app\Libraries\Report;

/***
 * ReportInterface defines the structure of the Report Builder
 */
interface ReportInterface
{
    /**
     * output
     *
     * @param  string $filename
     * @param  string $format
    * @return void
     */
    public function output(string $filename = 'php://output', $format = "CSV"): void;

    /**
     * setDataFromView
     *
     * @param  string $id
     * @return self
     */
    public function setDataFromView(string $id) : self;
    
    /**
     * setDataFromEntityRepository
     *
     * @param  string $view
     * @param  array $query
     * @param  string $search
     * @param  string $column
     * @param  string $order
     * @param  mixed $items
     * @param  bool $paginate
     * @return self
     */
    public function setDataFromEntityRepository(string $view = '', array $query = NULL, string $search = NULL, string $column = 'created_at', string $order = 'DESC', $items = 100, bool $paginate = false): self;

    public function getContentTypeHeaderBasedOnReportType(string $report_type = "CSV") : string;

}
