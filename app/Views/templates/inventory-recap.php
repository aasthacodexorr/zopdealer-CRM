<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Recap # <?php echo $stock_no; ?></title>
    <style>
        @import url('http://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        table,
        tr,
        td,
        th,
        tbody,
        thead,
        tfoot,
        td div {
            page-break-inside: avoid !important;
        }

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
            border: 2px solid black;
            border-bottom: none;
        }

        .table td {
            border-top: none;
        }

        .table tr:last-child td,
        .table th {
            border-bottom: 2px solid black;
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

<body class="container">
    <div style="text-align: right;margin-right: 1em;"><?php echo date('Y-m-d g:ia', time()); ?></div>
    <?php
    $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
    $aggregate_discount = 0;
    $aggregate_expenses = 0;
    $purchase_price = 0;
    $aggregate_taxes = 0;
    ?>
    <h4 class="heading">VEHICLE DETAILS</h4>
    <table class="item table inventory-details" style="width: 99%;">
        <thead>
            <tr>
                <th>YEAR</th>
                <th>MAKE</th>
                <th>MODEL</th>
                <th>TRIM</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $year; ?></td>
                <td><?php echo $make; ?></td>
                <td><?php echo $model; ?></td>
                <td> <span style="white-space:nowrap;display:inline-block;max-width:150px;text-overflow: ellipsis;"><?php echo $trim; ?></span></td>
            </tr>
        </tbody>
    </table>
    <table class="item table inventory-details" style="width: 99%;">
        <thead>
            <tr>
                <th>STOCK NO</th>
                <th>VIN</th>
                <th>ODOMETER READING</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $stock_no; ?></td>
                <td><?php echo $vin; ?></td>
                <td><?php echo $odometer; ?> <?php echo $km_miles; ?></td>
            </tr>
        </tbody>
    </table>
    <table class="item table inventory-details" style="width: 99%;">
        <thead>
            <tr>
                <th>LOCATION</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $location; ?></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <?php if (!empty($purchase)) { ?>
        <h4 class="heading">SELLER INFORMATION</h4>
        <table class="item table purchase-table" style="width: 99%;">
            <tbody>
                <tr>
                    <td>PURCHASE DATE</td>
                    <td><?php echo $purchase['purchase_date']; ?></td>
                </tr>
                <tr>
                    <td>COMPANY NAME</td>
                    <td><?php echo $purchase['company_name']; ?></td>
                </tr>
                <tr>
                    <td>NAME</td>
                    <td><?php echo $purchase['first_name']; ?> <?php echo $purchase['last_name']; ?></td>
                </tr>
                <tr>
                    <td>TAX #</td>
                    <td><?php echo $purchase['tax_number']; ?></td>
                </tr>
                <tr>
                    <td>SALESPERSON REG #</td>
                    <td><?php echo $purchase['sales_person_registration']; ?></td>
                </tr>
                <tr>
                    <td>STREET ADDRESS</td>
                    <td>
                        <?php echo $purchase['apt']; ?> <?php echo $purchase['street_address']; ?> <br>
                        <?php echo $purchase['city']; ?> / <?php echo $purchase['province']; ?> / <?php echo $purchase['postal_code']; ?> <br>
                        <?php echo $purchase['country']; ?>
                    </td>
                </tr>
                <tr>
                    <td>PHONE</td>
                    <td><?php echo $purchase['phone']; ?> / <?php echo $purchase['mobile']; ?></td>
                </tr>
                <tr>
                    <td>EMAIL</td>
                    <td><?php echo $purchase['email']; ?></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
    <h4 class="heading">EXPENSES</h4>
    <table class="item table" style="width: 99%;">
        <thead>
            <tr>
                <th style="min-width: 100px;">DATE</th>
                <th style="min-width: 200px;">DETAILS</th>
                <th>REF</th>
                <th>PRICE</th>
                <th>QTY</th>
                <th style="width: 100px">DIS</th>
                <th style="width: 100px" class="text-center"> TAX 1</th>
                <th class="text-center">TAX 2</th>
                <th>SUB TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expenses)) { ?>
                <?php foreach ($expenses as $expense) { ?>
                    <tr>
                        <td class="text-center"><?php echo $expense['purchase_date']; ?></td>
                        <td class="text-center"><span class="heading"><?php echo $expense['name']; ?></span> - <i><?php echo $expense['vendor_name']; ?></i></td>
                        <td class="text-center"><?php echo $expense['invoice']; ?></td>
                        <td class="text-center"><?php echo $expense['price']; ?></td>
                        <td class="text-center">
                            <?php
                            $line_price = is_numeric($expense['price']) ? (float) $expense['price'] : 0;
                            $line_quantity = is_numeric($expense['quantity']) ? (int) $expense['quantity'] : 0;
                            $line_expense = $line_price * $line_quantity;
                            $aggregate_expenses += $line_expense;
                            echo $expense['quantity'];
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $line_discount = is_numeric($expense['discount']) ? (float) $expense['discount'] : 0;
                            $aggregate_discount += $line_discount;
                            echo $expense['discount'];
                            ?>
                        </td>
                        <td class="text-center"><?php echo $expense['tax_amount']; ?></td>
                        <td class="text-center">
                            <?php
                            $line_tax_amount_total = is_numeric($expense['tax_amount_total']) ? (float) $expense['tax_amount_total'] : 0;
                            $aggregate_taxes += $line_tax_amount_total;
                            echo $expense['tax_amount_secondary'];
                            ?>
                        </td>
                        <td class="text-center"><?php echo $expense['sub_total']; ?></td>
                    </tr>
            <?php }
            } ?>
            <tr>
                <td colspan="3" rowspan="5" style="border-top: 2px solid black;border-bottom: 2px solid black;"></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;"><b>Vehicle Purchase Price<b></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;text-align:center;letter-spacing:2px;">
                    <?php
                    $purchase_price = is_numeric($purchase['price']) ? (float) $purchase['price'] : 0;
                    echo $fmt->formatCurrency($purchase_price, 'USD');
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;"><b>Additional Expenses Total<b></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;text-align:center;letter-spacing:2px;">
                    <?php
                    echo $fmt->formatCurrency($aggregate_expenses, 'USD');
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;"><b>Discount<b></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;text-align:center;letter-spacing:2px;">
                    <?php
                    $line_discount = is_numeric($purchase['discount']) ? (float) $purchase['discount'] : 0;
                    $aggregate_discount += $line_discount;
                    echo $fmt->formatCurrency($aggregate_discount, 'USD');
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;"><b>Total Taxes<b></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;text-align:center;letter-spacing:2px;">
                    <?php
                    $line_tax_amount_total = is_numeric($purchase['tax_amount_total']) ? (float) $purchase['tax_amount_total'] : 0;
                    $aggregate_taxes += $line_tax_amount_total;
                    echo $fmt->formatCurrency($aggregate_taxes, 'USD');
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;"><b>Total Expense (Including Taxes)<b></td>
                <td colspan="3" style="border-top: 2px solid black;font-size:1.2em;text-align:center;letter-spacing:2px;">
                    <?php
                    $total_expenses_after_taxes = $purchase_price + $aggregate_expenses - $aggregate_discount + $aggregate_taxes;
                    echo $fmt->formatCurrency($total_expenses_after_taxes, 'USD');
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>