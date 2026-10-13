<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Order</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .thead-dark {
            background-color: #40566f;
            border-color: #40566f;
            color: #fff;
        }

        .table th,
        .table td {
            padding: 0.5em 0.25em;
            vertical-align: top;
            border-bottom: none;
        }

        .table td {
            border-top: none;
        }

        .table tr:last-child td,
        .table th {
            /* border-bottom: 2px solid black; */
        }

        .p-50 {
            padding: 0.25em !important;
        }

        .heading {
            font-size: 1em;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .item {
            margin: 2px;
        }

        .text-center {
            text-align: center;
        }

        th,
        td {
            font-size: 1em;
        }

        table {
            border-collapse: collapse;
        }

        .inventory-details th {
            width: 25%;
            font-size: 0.7em;
            border: none;
            background: #e7e7e7;
        }

        .inventory-details tr td {
            border: none !important;
            font-size: 1em;
            text-align: center;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;

        }

        .work-jobs-details th {
            width: 25%;
            font-size: 0.7em;
            border: none;
            background: #e7e7e7;
        }

        .work-jobs-details tr td {
            border: none !important;
            font-size: 1em;
            text-align: center;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid #eee !important;

        }

        h4 {
            text-decoration: underline;
            padding-bottom: 4px;
        }

        .purchase-table tbody td {
            border: none !important;
            font-size: 1em;
        }
    </style>
</head>

<body class="container" style="height:100%;">
    <div class="row">
        <div class="d-inline-block" style="display:inline-block;width:49%;"><img src="<?php

                                                                                        use App\Helpers\Utility;

                                                                                        $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
                                                                                        echo $logo; ?>" style="width:200px;" /></div>
        <div class="d-inline-block" style="display:inline-block;width:49%;text-align:right;vertical-align:top;"><span><?php echo date('Y-m-d g:ia', time()); ?></span></div>
    </div>
    <div class="row">
        <div class="d-inline-block" style="display:inline-block;width:33%;vertical-align:text-top">
            <h3><?php echo $location['business_name']; ?></h3>
            <p class=""><?php echo $location['street']; ?>, <br>
                <?php echo $location['city']; ?>, <?php echo $location['state']; ?>, <?php echo $location['zipcode']; ?>. <br>
                Tel : <?php echo Utility::format_phone_us($location['phone']) ?> <br>
                Email: <?php echo $location['email']; ?> <br>
                Tax # <?php echo $location['tax_number']; ?>
            </p>
        </div>

        <div class="d-inline-block" style="display:inline-block;width:33%;vertical-align:text-top;">
            <?php if (!empty($inventory)) { ?>

                <h3 class=""><?php echo $inventory['year']; ?> <?php echo $inventory['make']; ?> <?php echo $inventory['model']; ?> </h3>
                <p class="">
                    ODOMETER : <?php echo $inventory['odometer']; ?> <?php echo $inventory['km_miles']; ?> <br>
                    VIN: <?php echo $inventory['vin']; ?> <br>
                    STOCK NO: <?php echo $inventory['stock_no']; ?> <br>
                </p>
            <?php } ?>

        </div>
        <div class="d-inline-block" style="display:inline-block;width:33%;vertical-align:text-top;">
            <?php if (!empty($customer)) { ?>

                <h3><?php echo $customer['first_name']; ?> <?php echo $customer['last_name']; ?></h3>
                <p class=""><?php echo $customer['apt']; ?> <?php echo $customer['street_address']; ?>, <br>
                    <?php echo $customer['city']; ?>, <?php echo $customer['province']; ?>, <?php echo $customer['postal_code']; ?>. <br>
                    Tel : <?php echo Utility::format_phone_us($customer['phone']) ?> <br>
                    Email: <?php echo $customer['email']; ?> <br>
                </p>
            <?php } ?>

        </div>
        <table class="item table inventory-details" style="width: 99%;">
            <thead>
                <tr>
                    <th class="col text-center">WORK ORDER ID</th>
                    <th class="col text-center">CHECK-IN DATE</th>
                    <th class="col text-center">CHECK-IN ODOMETER</th>
                    <th class="col text-center">ADVISOR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>WO-<?php echo $work_order['id']; ?></td>
                    <td><?php echo $work_order['check_in_date_time']; ?></td>
                    <td><?php echo $work_order['check_in_odometer']; ?></td>
                    <td><?php echo $work_order['assigned_advisor']; ?></td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($work_jobs['data_list']) && is_array($work_jobs['data_list'])) { ?>
            <?php foreach ($work_jobs['data_list'] as $key => $work_job) { ?>
                <table class="item table work-jobs-details" style="width: 99%;">
                    <thead>
                        <tr>
                            <th colspan="2" class="col" style="text-align: left;background: #f4f4f4;">
                                <h3> <?php echo $work_job['title']; ?> </h3>
                            </th>
                        </tr>
                        <tr>
                            <th class="col" style="text-align: left;min-width: 300px;">Details</th>
                            <th class="col" style="text-align: center;min-width:75px;">Qty/Hrs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($work_job['products'] as $product) { ?>
                            <?php if($product['type'] !== 'Consumable'){ ?>
                            <tr>
                                <td>
                                    <div style="font-size:0.8em;text-align:left;"><?php echo $product['name']; ?> / <?php echo $product['item_code']; ?></div>
                                    <p style="font-size:0.8em;text-align:left;font-weight:400;"><?php echo Utility::trimString($product['description'], 200); ?></p>
                                </td>
                                <td style="text-align:center;"><?php echo $product['quantity']; ?></td>
                            </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="row" style="width: 99%;align-self:flex-end">
        <div style="display:block;vertical-align:text-bottom">
            <table class="item table" style="width: 99%;">
                <thead>
                    <tr>
                        <th style="text-align:center;"> <br><br>
                            <hr> Approver's Signature
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align:center;"> <br><br>
                            <hr> Customer's Signature
                        </th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</body>

</html>