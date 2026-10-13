<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php

            use Carbon\Carbon;

            echo $pdf_title; ?>
    </title>

    <link rel="stylesheet" href="http://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* Snappy/wkhtmltopdf-friendly CSS
       - no CSS variables
       - no flexbox dependency
       - minimal local Bootstrap fallbacks so the PDF does not rely on remote CSS
       - explicit print/page-break rules
    */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.35;
            color: #222222;
            background: #ffffff;
        }

        .report-page {
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            padding: 16px;
        }

        /* Local fallbacks for Bootstrap classes used in this file. */
        .mb-0 {
            margin-bottom: 0 !important;
        }

        .mb-2 {
            margin-bottom: 8px !important;
        }

        .mt-1 {
            margin-top: 4px !important;
        }

        .d-print-none {
            display: none !important;
        }

        .d-flex {
            display: table;
            width: 100%;
        }

        .d-flex>div {
            display: table-cell;
            vertical-align: top;
        }

        .justify-content-between,
        .align-items-start {
            /* Kept as harmless Bootstrap fallbacks for wkhtmltopdf. */
        }

        .btn {
            display: none !important;
        }

        .table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            table-layout: fixed;
            background-color: #ffffff;
        }

        .table th,
        .table td {
            border: 1px solid #d9dde3;
        }

        .table-sm th,
        .table-sm td {
            padding: 5px 7px;
        }

        .table-bordered {
            border: 1px solid #d9dde3;
        }

        .report-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            background-color: #343a40;
            color: #ffffff;
            padding: 6px 8px;
            margin: 12px 0 0;
            border: 1px solid #343a40;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #d9dde3;
            margin-bottom: 0;
        }

        .info-row {
            display: table-row;
        }

        .info-label,
        .info-value {
            display: table-cell;
            padding: 5px 8px;
            min-height: 24px;
            border-bottom: 1px solid #d9dde3;
            vertical-align: top;
        }

        .info-label {
            width: 145px;
            font-weight: 700;
            background-color: #f6f8fa;
            border-right: 1px solid #d9dde3;
            white-space: nowrap;
        }

        .info-row:last-child .info-label,
        .info-row:last-child .info-value {
            border-bottom: 0;
        }

        .inventory-table th,
        .inventory-table td,
        .checklist-table th,
        .checklist-table td {
            padding: 5px 7px !important;
            vertical-align: top !important;
            border-color: #d9dde3 !important;
            word-wrap: break-word;
        }

        .inventory-table th,
        .checklist-table th {
            background-color: #f6f8fa;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            font-weight: 700;
        }

        .checklist-header-row td {
            background-color: #e9ecef;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #222222;
            border-top: 2px solid #adb5bd !important;
        }

        .status-badge {
            display: inline-block;
            width: 48px;
            padding: 2px 6px;
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            line-height: 1.35;
            border: 1px solid #d9dde3;
            white-space: nowrap;
        }

        .status-pass {
            color: #155724;
            background-color: #eefaf1;
            border-color: #b7e4c7;
        }

        .status-fail {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #dc3545;
        }

        .status-na {
            color: #495057;
            background-color: #f1f3f5;
            border-color: #ced4da;
        }

        .fail-row td {
            background-color: #fff0f0;
            border-top: 1px solid #dc3545 !important;
            border-bottom: 1px solid #dc3545 !important;
        }

        .fail-reason {
            margin-top: 3px;
            font-size: 11px;
            color: #721c24;
            font-weight: 600;
        }

        .comment-label {
            font-weight: 700;
            color: #555555;
        }

        .small-muted {
            color: #6c757d;
            font-size: 11px;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr,
        td,
        th,
        .section-title,
        .info-row {
            page-break-inside: avoid;
        }

        .attachment-images {
            margin-top: 4px;
        }

        .attachment-links a {
            font-size: 11px;
            color: #007bff;
            text-decoration: underline;
            word-break: break-all;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            html,
            body {
                font-size: 10.5px;
                -webkit-print-color-adjust: exact;
            }

            .report-page {
                max-width: none;
                padding: 0;
            }
        }

        .info-value.incomplete {
            color: #721c24 !important;

        }

        .appendix-section {
            margin-top: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #d9dde3;
            page-break-inside: avoid;
        }

        .appendix-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #222;
        }

        .appendix-images img {
            width: 220px;
            margin-right: 8px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            padding: 2px;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="report-page">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <div class="report-title"><?php echo $title; ?> </div>
                <div class="small-muted"><?php echo $description; ?></div>
            </div>
            <button class="btn btn-sm btn-outline-secondary d-print-none" onclick="window.print()">Print</button>
        </div>

        <div class="section-title">Report Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Title</div>
                <div class="info-value"><?php echo $title; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Description</div>
                <div class="info-value"><?php echo $description; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Odometer</div>
                <div class="info-value"><?php echo $odometer; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Date</div>
                <div class="info-value"><?php echo Carbon::parse($created_at)->format("m/d/Y") ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value <?php echo strtolower($status); ?> "><?php echo $status; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Assignee</div>
                <div class="info-value"><?php echo $assignee; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Issues Count</div>
                <div class="info-value"><?php echo $issues_count; ?></div>
            </div>
        </div>

        <?php if (!empty($stock_no) && !empty($stock_no)) { ?>
            <div class="section-title">Inventory</div>
            <table class="table table-bordered table-sm inventory-table mb-0">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Trim</th>
                        <th>VIN</th>
                        <th>Stock #</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $inventory['year']; ?></td>
                        <td><?php echo $inventory['make']; ?></td>
                        <td><?php echo $inventory['model']; ?></td>
                        <td><?php echo $inventory['trim']; ?></td>
                        <td><?php echo $inventory['vin']; ?></td>
                        <td><?php echo $inventory['stock_no']; ?></td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>

        <div class="section-title">Items</div>
        <?php if (!empty($items)) { ?>
            <table class="table table-bordered table-sm checklist-table mb-0">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Item</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:36%;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 0; ?>
                    <?php foreach ($items as $item) {  ?>
                        <?php if (strtoupper($item["type"]) === "HEADER") { ?>
                            <tr class="checklist-header-row">
                                <td colspan="4"><?php echo $item['title']; ?></td>
                            </tr>
                        <?php } else if (strtoupper($item["type"]) === "COMMENT") { ?>
                            <tr>
                                <td><?php echo ++$count; ?></td>
                                <td colspan="3"><span class="comment-label"><?php echo $item['title']; ?>:</span> <?php echo $item['comment']; ?></td>
                            </tr>
                        <?php } else if (strtoupper($item["type"]) === "PASSFAIL" || strtoupper($item["type"]) === "PASSFAILWITHNA") { ?>
                            <tr class="<?php echo strtolower($item['status']); ?>-row">
                                <td><?php echo ++$count; ?></td>
                                <td><?php echo $item['title']; ?></td>
                                <td><?php if (!empty($item['status'])) { ?>
                                        <span class="status-badge status-<?php echo strtolower($item['status']); ?>"><?php echo $item['status']; ?>
                                        <?php } ?>
                                        </span>
                                </td>
                                <td><?php echo $item['description']; ?>
                                    <?php if (!empty($item['reason'])) { ?>
                                        <div class="<?php echo strtolower($item['status']); ?>-reason">Reason: <?php echo $item['reason']; ?></div>
                                    <?php } ?>
                                    <?php if (count($item['attachments']) > 0) { ?>
                                        <div class="small-muted">Attachments:</div>
                                        <div class="attachment-links">
                                            <?php $attachment_count = 0; ?>
                                            <?php foreach ($item['attachments'] as $attachment) { ?>
                                                <a href="<?php echo getenv('image.cdn') . $attachment['url']; ?>" target="_blank">View Attachment <?php echo ++$attachment_count; ?></a><br>

                                            <?php } ?>

                                        </div>
                                    <?php } ?>

                                </td>
                            </tr>

                    <?php  }
                    } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <div class="section-title">Appendix</div>

    <?php if (!empty($items)) { ?>
        <?php foreach ($items as $item) {  ?>
            <?php if (count($item['attachments']) > 0) { ?>
                <div class="appendix-section">
                    <div class="appendix-title">
                        <?php echo $item['title']; ?>
                    </div>

                    <div class="appendix-images">
                        <?php foreach ($item['attachments'] as $attachment) { ?>
                            <a href="<?php echo getenv('image.cdn') . $attachment['url']; ?>" target="_blank">
                                <?php
                                try {
                                    $image_url = getenv('image.cdn') . $attachment['url'];
                                    $image = file_get_contents($image_url . '?width=300');

                                ?>
                                    <img src="data:image/png;base64,<?php echo base64_encode($image); ?>">
                                <?php
                                } catch (\Exception $ex) {
                                    //throw $th;
                                } catch (\Error $ex) {
                                    //throw $th;
                                }

                                ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

    <?php } ?>
</body>

</html>