<?php

namespace Tenant\app\Repositories;

/**
 * DashboardRepository This repository provides aggregate of the data in the database.
 * 
 */
class DashboardRepository
{
    /**
     * summary This method summarized the data to be sent to the dashboard.
     *
     * @param  mixed $timeperiod
     * @return array
     */
    public function summary($timeperiod = "all") : array
    {
        try {
            $db = db_connect();
            $query = $db->query($this->queryString($timeperiod));
            $query = $query->getRowArray();
            return $query;
        } catch (\Exception $ex) {
            return ["errors" => $ex->getMessage()];
        }
    }



    /**
     * queryString his function simply returns the query as a string along with filters.
     *
     * @param  String $timeperiod
     * @return String
     */
    protected function queryString(String $timeperiod = "all"): String
    {

        switch ($timeperiod) {
            case 'month':
                return '
                SELECT (SELECT COUNT(*) from inventory WHERE inventory.deleted_at IS NULL AND YEAR(stock_date)=YEAR(now()) AND MONTH(stock_date)=MONTH(now())) as inventory_count,
                (SELECT COUNT(*) from lead_responses where lead_responses.deleted_at IS NULL AND YEAR(lead_responses.created_at)=YEAR(now()) AND MONTH(lead_responses.created_at)=MONTH(now()) ) as lead_responses_count,
                (SELECT COUNT(*) from deals WHERE deals.deleted_at IS NULL AND YEAR(deals.created_at)=YEAR(now()) AND MONTH(deals.created_at)=MONTH(now())) as deals_count,
                (SELECT SUM(IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) from inventory WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0"  AND YEAR(stock_date)=YEAR(now()) AND MONTH(stock_date)=MONTH(now()) ) as retail_total,
                COALESCE((SELECT SUM((inventory_costs.price * inventory_costs.quantity) - inventory_costs.discount) FROM inventory_costs JOIN inventory ON inventory.inventory_id = inventory_costs.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_costs.deleted_at IS NULL AND YEAR(inventory_costs.created_at)=YEAR(now()) AND MONTH(inventory_costs.created_at)=MONTH(now()) ), 0) as expenses,
                COALESCE((SELECT SUM(inventory_purchase.price - inventory_purchase.discount) FROM inventory_purchase JOIN inventory ON inventory.inventory_id = inventory_purchase.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_purchase.deleted_at IS NULL AND YEAR(inventory_purchase.created_at)=YEAR(now()) AND MONTH(inventory_purchase.created_at)=MONTH(now()) ), 0) as vehicle_purchases,
                COALESCE((SELECT SUM((deal_base_breakdown.purchase_price)) FROM deal_base_breakdown JOIN deals ON deal_base_breakdown.deal_id = deals.id AND deals.status ="Delivered" AND YEAR(deals.created_at)=YEAR(now())  AND MONTH(deals.created_at)=MONTH(now()) ), 0) as vehicle_sales,
                (SELECT vehicle_sales - vehicle_purchases - expenses) as vehicle_profit_loss
                
                ';
                break;

            case 'year':
                return '
                SELECT (SELECT COUNT(*) from inventory WHERE inventory.deleted_at IS NULL AND YEAR(stock_date)=YEAR(now())) as inventory_count,
                (SELECT COUNT(*) from lead_responses where lead_responses.deleted_at IS NULL AND YEAR(lead_responses.created_at)=YEAR(now())) as lead_responses_count,
                (SELECT COUNT(*) from deals WHERE deals.deleted_at IS NULL AND YEAR(deals.created_at)=YEAR(now())) as deals_count,
                (SELECT SUM(IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) from inventory WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND YEAR(stock_date)=YEAR(now()) ) as retail_total,
                COALESCE((SELECT SUM((inventory_costs.price * inventory_costs.quantity) - inventory_costs.discount) FROM inventory_costs JOIN inventory ON inventory.inventory_id = inventory_costs.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_costs.deleted_at IS NULL AND YEAR(inventory_costs.created_at)=YEAR(now()) ), 0) as expenses,
                COALESCE((SELECT SUM(inventory_purchase.price - inventory_purchase.discount) FROM inventory_purchase JOIN inventory ON inventory.inventory_id = inventory_purchase.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_purchase.deleted_at IS NULL AND YEAR(inventory_purchase.created_at)=YEAR(now()) ), 0) as vehicle_purchases,
                COALESCE((SELECT SUM((deal_base_breakdown.purchase_price)) FROM deal_base_breakdown JOIN deals ON deal_base_breakdown.deal_id = deals.id AND deals.status ="Delivered" AND YEAR(deals.created_at)=YEAR(now()) ), 0) as vehicle_sales,
                (SELECT vehicle_sales - vehicle_purchases - expenses) as vehicle_profit_loss
                
                ';
                break;
            default:
                return '
                    SELECT (SELECT COUNT(*) from inventory where inventory.deleted_at IS NULL) as inventory_count,
                    (SELECT COUNT(*) from lead_responses where lead_responses.deleted_at IS NULL) as lead_responses_count,
                    (SELECT COUNT(*) from deals where deals.deleted_at IS NULL) as deals_count,
                    (SELECT SUM(IF(inventory.special_price_on < 1, inventory.price, inventory.special_price )) from inventory WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" ) as retail_total,
                    COALESCE((SELECT SUM((inventory_costs.price * inventory_costs.quantity) - inventory_costs.discount) FROM inventory_costs JOIN inventory ON inventory.inventory_id = inventory_costs.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_costs.deleted_at IS NULL ), 0) as expenses,
                    COALESCE((SELECT SUM(inventory_purchase.price - inventory_purchase.discount) FROM inventory_purchase JOIN inventory ON inventory.inventory_id = inventory_purchase.inventory_id WHERE inventory.deleted_at IS NULL AND inventory.status = "Instock" AND inventory.visibility != "0" AND inventory_purchase.deleted_at IS NULL ), 0) as vehicle_purchases,
                    COALESCE((SELECT SUM((deal_base_breakdown.purchase_price)) FROM deal_base_breakdown JOIN deals ON deal_base_breakdown.deal_id = deals.id AND deals.status ="Delivered" ), 0) as vehicle_sales,
                    (SELECT vehicle_sales - vehicle_purchases - expenses) as vehicle_profit_loss
                    
                    ';
                break;
        }
    }
}
