<?php

namespace App\Repositories;

use Carbon\Carbon;

/**
 * AdvanceRepository This class contains advance operations on model like creating joins, filtering.
 */
abstract class AdvanceRepository extends BaseRepository
{
    /**
     * index
     *
     * @param  int $id
     * @param  string $header
     * @param  JSON $view
     * @param  JSON $query
     * @param  int $items
     * @param  JSON $sort
     * @param  boolean $paginate
     * @param  string $view_id_title
     * @param  string $data_list
     * @return 
     */
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

    public function allQueries($query = null)
    {
        $queries = $query != null ? json_decode($query, true) : '';

        if (!empty($query)) {
            foreach ($queries as $index => $each_query) {
                //If #index is 0, it is first query else we need to check for OR or AND query.
                $this->mapQueries(
                    $each_query['column'],
                    $each_query['join'],
                    $each_query['comparator'],
                    $each_query['type'],
                    $each_query['value_1'],
                    $each_query['value_2']
                );
            }
        }
    }

    protected function mapQueries($column, $join, $comparator, $type, $value_1, $value_2)
    {
        switch ($type) {
            case 'Multi Select':
                if ($column == "vehicle_tags") {
                    $this->selectionFromArray($column, $join, $comparator, $value_1);
                } else {
                    $this->multiSelectQuery($column, $join, $comparator, $value_1);
                }

                break;

            case 'Null Comparision':
                $this->emptyNullQuery($column, $join, $comparator, $value_1);
                break;

            case 'Date Comparision':
                $this->dateComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;

            case 'Single Select':
                $this->singleSelectQuery($column, $join, $comparator, $value_1);
                break;

            case 'Numeric Comparision':
                $this->numericComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;

            case 'Numeric Range Comparision':
                $this->numericRangeComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;

            case 'Relative Time Period Comparision':
                $this->relativeTimeComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;

            case 'Relative Month Comparision':
                $this->relativeMonthComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;

            case 'Relative Year Comparision':
                $this->relativeYearComparisionQuery($column, $join, $comparator, $value_1, $value_2);
                break;


            default:
                # code...
                break;
        }
    }

    protected function multiSelectQuery($column, $join, $comparator, $value_1)
    {
        if ($join == "OR") {

            if ($comparator == "is") {
                $this->model->orWhereIn($this->model->table . '.' . $column, $value_1);
            } elseif ($comparator == "is not") {
                $this->model->orWhereNotIn($this->model->table . '.' . $column, $value_1);
            }
        } else {

            if ($comparator == "is") {
                $this->model->whereIn($this->model->table . '.' . $column, $value_1);
            } elseif ($comparator == "is not") {
                $this->model->whereNotIn($this->model->table . '.' . $column, $value_1);
            }
        }
    }

    protected function selectionFromArray($column, $join, $comparator, $value_1)
    {
        if ($join == "OR") {

            if ($comparator == "is") {
                foreach ($value_1 as $value) {
                    $this->model->orLike($this->model->table . '.' . $column, $value);
                }
            } elseif ($comparator == "is not") {
                foreach ($value_1 as $value) {
                    $this->model->orNotLike($this->model->table . '.' . $column, $value);
                }
            }
        } else {

            if ($comparator == "is") {
                foreach ($value_1 as $value) {
                    $this->model->like($this->model->table . '.' . $column, $value);
                }
            } elseif ($comparator == "is not") {
                foreach ($value_1 as $value) {
                    $this->model->notLike($this->model->table . '.' . $column, $value);
                }
            }
        }
    }

    protected function emptyNullQuery($column, $join, $comparator, $value_1)
    {
        if ($join == "OR") {

            if ($comparator == "is empty") {
                $this->model->orWhere($this->model->table . '.' . $column, "");
            } elseif ($comparator == "is not empty") {
                $this->model->orWhereNotIn($this->model->table . '.' . $column, [""]);
            }
        } else {

            if ($comparator == "is empty") {
                $this->model->where($this->model->table . '.' . $column, "");
            } elseif ($comparator == "is not empty") {
                $this->model->whereNotIn($this->model->table . '.' . $column, [""]);
            }
        }
    }

    protected function singleSelectQuery($column, $join, $comparator, $value_1)
    {
        if ($join == "OR") {
            $this->model->orWhere($this->model->table . '.' . $column, $value_1);
        } else {
            $this->model->where($this->model->table . '.' . $column, $value_1);
        }
    }

    protected function numericComparisionQuery($column, $join, $comparator, $value_1, $value_2)
    {
        if ($comparator == 'between') {
            if ($join == "OR") {
                $this->model->orWhere($this->model->table . '.' . $column . " BETWEEN " . $value_1 . " AND " . $value_2, null, false);
            } else {
                $this->model->where($this->model->table . '.' . $column . " BETWEEN " . $value_1 . " AND " . $value_2, null, false);
            }
        } else {
            $comparision = "";
            if ($comparator == "greater than") {
                $comparision = " > ";
            } elseif ($comparator == "less than") {
                $comparision = " < ";
            }

            if ($join == "OR") {
                $this->model->orWhere($this->model->table . '.' . $column . $comparision, $value_1);
            } else {
                $this->model->where($this->model->table . '.' . $column . $comparision, $value_1);
            }
        }
    }

    protected function dateComparisionQuery($column, $join, $comparator, $value_1, $value_2)
    {
        $value_1 = Carbon::createFromIsoFormat('MM/DD/YYYY', $value_1, 'UTC');
        $start_day = $value_1->copy()->addUnitNoOverflow('hour', -24, 'day');
        $end_day =  $value_1->copy()->addUnitNoOverflow('hour', 24, 'day');

        if (empty($value_2)) {
            $value_2 = $end_day;
        } else {
            $value_2 = Carbon::createFromIsoFormat('MM/DD/YYYY', $value_2, 'UTC');
            $end_day_2 =  $value_2->copy()->addUnitNoOverflow('hour', 24, 'day');
        }

        if ($join == "OR") {
            if ($comparator == "is") {
                $this->model->orWhere($this->model->table . '.' . $column . " <= '" . $end_day . "' and " . $this->model->table . '.' . $column . " >= '" . $start_day . "' ");
            } else if ($comparator == "before") {
                $this->model->orWhere($this->model->table . '.' . $column . " < ", $start_day);
            } else if ($comparator == "after") {
                $this->model->orWhere($this->model->table . '.' . $column . " > ", $end_day);
            } else if ($comparator == "between") {
                $this->model->orWhere($this->model->table . '.' . $column . " <= '" . $end_day_2 . "' and " . $this->model->table . '.' . $column . " >= '" . $start_day . "' ");
            }
        } else {
            if ($comparator == "is") {
                $this->model->where($this->model->table . '.' . $column . " <= '" . $end_day . "' and " . $this->model->table . '.' . $column . " >= '" . $start_day . "' ");
            } else if ($comparator == "before") {
                $this->model->where($this->model->table . '.' . $column . " < ", $start_day);
            } else if ($comparator == "after") {
                $this->model->where($this->model->table . '.' . $column . " > ", $end_day);
            } else if ($comparator == "between") {
                $this->model->where($this->model->table . '.' . $column . " <= '" . $end_day_2 . "' and " . $this->model->table . '.' . $column . " >= '" . $start_day . "' ");
            }
        }
    }

    protected function relativeTimeComparisionQuery($column, $join, $comparator, $value_1, $value_2)
    {
        //first set the date for today
        $today = Carbon::today('UTC');

        //get the date for dates on relative days from $value1 and $value2
        if (is_numeric($value_1)) {
            $limit_value_1 = $today->copy()->subDays($value_1);
        }

        if (is_numeric($value_2)) {
            $limit_value_2 = $today->copy()->subDays($value_2);
        }

        if ($join == "OR") {
            //join OR
            if ($comparator == "greater than") {
                $this->model->orWhere($this->model->table . '.' . $column . " < ", $limit_value_1);
            } else if ($comparator == "less than") {
                $this->model->orWhere($this->model->table . '.' . $column . " > ", $limit_value_1);
            } else if ($comparator == "between") {
                $this->model->orWhere($this->model->table . '.' . $column . " <= '" . $limit_value_1 . "' and " . $this->model->table . '.' . $column . " >= '" . $limit_value_2 . "' ");
            }
        } else {
            //join AND
            if ($comparator == "greater than") {
                $this->model->where($this->model->table . '.' . $column . " < ", $limit_value_1);
            } else if ($comparator == "less than") {
                $this->model->where($this->model->table . '.' . $column . " > ", $limit_value_1);
            } else if ($comparator == "between") {
                $this->model->where($this->model->table . '.' . $column . " <= '" . $limit_value_1 . "' and " . $this->model->table . '.' . $column . " >= '" . $limit_value_2 . "' ");
            }
        }
    }

    /**
     * numericRangeComparisionQuery
     *
     * @param  mixed $column
     * @param  mixed $join
     * @param  mixed $comparator
     * @param  mixed $value_1
     * @param  mixed $value_2 Float precision for the number
     * @return void
     */
    protected function numericRangeComparisionQuery($column, $join, $comparator, $value_1, $value_2 = 1)
    {

        $range = [];
        if (gettype($value_1) == 'array') {
            foreach ($value_1 as $each_range_value) {
                $single_range = explode('-', $each_range_value);
                array_push($range, range($single_range[0], $single_range[1], $value_2));
            }
        } else if (gettype($value_1) == 'string') {
            $single_range = explode('-', $value_1);
            array_push($range, range($single_range[0], $single_range[1], $value_2));
        }

        $range = array_merge(...$range);

        if ($join == "OR") {
            $this->model->orWhereIn($this->model->table . '.' . $column, $range);
        } else {
            $this->model->whereIn($this->model->table . '.' . $column, $range);
        }
    }

    /**
     * relativeMonthComparisionQuery This function is related to years relative to today's date.
     *
     * @param  mixed $column
     * @param  mixed $join
     * @param  mixed $comparator
     * @param  mixed $value_1
     * @param  mixed $value_2
     * @return void
     */
    protected function relativeMonthComparisionQuery($column, $join, $comparator, $value_1 = 0, $value_2 = 0)
    {

        if (!is_numeric($value_1)) {
            $value_1 = 0;
        }

        if (!is_numeric($value_2)) {
            $value_2 = 0;
        }
        if ($join == "OR") {
            if ($comparator == "is") {
                $this->model->orWhere("MONTH({$this->model->table}.{$column})=MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            } else if ($comparator == "before") {
                $this->model->orWhere("MONTH({$this->model->table}.{$column})<MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            } else if ($comparator == "after") {
                $this->model->orWhere("MONTH({$this->model->table}.{$column})>MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            }
        } else {

            if ($comparator == "is") {
                $this->model->where("MONTH({$this->model->table}.{$column})=MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            } else if ($comparator == "before") {
                $this->model->where("MONTH({$this->model->table}.{$column})<MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            } else if ($comparator == "after") {
                $this->model->where("MONTH({$this->model->table}.{$column})>MONTH(CURRENT_DATE - INTERVAL {$value_1} MONTH)", null, false);
            }
        }
    }


    /**
     * relativeYearComparisionQuery This function is related to years relative to today's date.
     *
     * @param  mixed $column
     * @param  mixed $join
     * @param  mixed $comparator
     * @param  mixed $value_1
     * @param  mixed $value_2
     * @return void
     */
    protected function relativeYearComparisionQuery($column, $join, $comparator, $value_1 = 0, $value_2 = 0)
    {

        if (!is_numeric($value_1)) {
            $value_1 = 0;
        }

        if (!is_numeric($value_2)) {
            $value_2 = 0;
        }
        if ($join == "OR") {
            if ($comparator == "is") {
                $this->model->orWhere("YEAR({$this->model->table}.{$column})=YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            } else if ($comparator == "before") {
                $this->model->orWhere("YEAR({$this->model->table}.{$column})<YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            } else if ($comparator == "after") {
                $this->model->orWhere("YEAR({$this->model->table}.{$column})>YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            }
        } else {

            if ($comparator == "is") {
                $this->model->where("YEAR({$this->model->table}.{$column})=YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            } else if ($comparator == "before") {
                $this->model->where("YEAR({$this->model->table}.{$column})<YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            } else if ($comparator == "after") {
                $this->model->where("YEAR({$this->model->table}.{$column})>YEAR(CURRENT_DATE - INTERVAL {$value_1} YEAR)", null, false);
            }
        }
    }

    /**
     * search This is used to perform a phonetic search
     *
     * @param  mixed $search
     * @return void
     */
    public function search(string $search)
    {
        $search_terms = explode(' ', $search);
        foreach ($search_terms as $search_term) {
            $search_term = is_numeric($search_term) ? $search_term : metaphone($search_term);
            $this->model->like("{$this->model->table}.phonetic_string", $search_term);
        }
        return $this;
    }

    /**
     * exactSearch This method performs an exact search on the keywords
     *
     * @param  mixed $search
     */
    public function exactSearch(string $search)
    {
        $search_terms = explode(' ', $search);
        foreach ($search_terms as $search_term) {
            $search_term = is_numeric($search_term) ? $search_term : strtolower($search_term);
            $this->model->like("{$this->model->table}.search_keyword", $search_term);
        }
        return $this;
    }

    /**
     * filterData 
     *
     * @param  mixed $column
     * @param  mixed $value
     * 
     */
    public function filterData($column, $value)
    {
        $this->model->where($this->model->table . '.' . $column, $value);
        return $this;
    }


    /**
     * filterDataByMultipleValues
     *
     * @param  mixed $column
     * @param  mixed $value
     */
    public function filterDataByMultipleValues($column, $value)
    {
        $this->model->whereIn($this->model->table . '.' . $column, $value);
        return $this;
    }

    public function filterOnCurrentMonthAndYear($column)
    {
        $this->model->where("MONTH({$column})=MONTH(now())", null, false);
        $this->model->where("YEAR({$column})=YEAR(now())", null, false);
        return $this;
    }

    public function filterOnToday($column)
    {
        $this->model->where("DATE({$column}) = curdate()", null, false);
        return $this;
    }
}
