<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Drive Report</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #2c2c2c; background: #fff; }
        .page { padding: 20px 24px; }
        .clearfix::after { content:''; display:table; clear:both; }
        .col-half  { display: inline-block; width: 49%; vertical-align: top; }
        /* ── Header ── */
        .report-header { border-bottom: 3px solid #1a3a5c; padding-bottom: 12px; margin-bottom: 16px; }
        .report-header img { max-height: 55px; max-width: 180px; }
        .report-title { font-size: 20px; font-weight: bold; color: #1a3a5c; }
        .report-meta  { font-size: 11px; color: #666; margin-top: 3px; }
        /* ── Status badge ── */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-pending     { background: #fff3cd; color: #856404; border: 1px solid #ffc107; }
        .badge-in_progress { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
        .badge-completed   { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-cancelled   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-default     { background: #e9ecef; color: #495057; border: 1px solid #ced4da; }
        /* ── Sections ── */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #1a3a5c; background: #eef2f7; padding: 5px 8px; margin-bottom: 8px; border-left: 4px solid #1a3a5c; }
        /* ── Info table ── */
        table.info { width: 100%; border-collapse: collapse; }
        table.info td { padding: 4px 6px; vertical-align: top; }
        table.info td.lbl { width: 38%; font-weight: bold; color: #555; white-space: nowrap; }
        table.info td.val { color: #2c2c2c; }
        /* ── Data table ── */
        table.data-table { width: 100%; border-collapse: collapse; }
        table.data-table thead th { background: #1a3a5c; color: #fff; padding: 5px 8px; font-size: 11px; text-align: left; }
        table.data-table tbody tr:nth-child(even) { background: #f5f7fa; }
        table.data-table tbody td { padding: 4px 8px; border-bottom: 1px solid #e0e4ea; vertical-align: top; font-size: 11px; }

        /* ── Photo grid (4-up) ── */
        .photo-item { display: inline-block; width: 23%; margin: 0 2% 8px 0; vertical-align: top; }
        /* Removes the right margin from every 4th image so the row doesn't break */
        .photo-item:nth-child(4n) { margin-right: 0; }
        .photo-item img {
            display: block !important;      /* Removes hidden spacing below inline images */
            width: 100% !important;         /* Locks the width to the container */
            height: auto !important;        /* OVERRIDES any hardcoded HTML heights */
            aspect-ratio: 1 / 1 !important; /* FORCES the strict perfect square shape */
            object-fit: contain !important; /* Prevents cropping the cars */
            background: #f5f7fa;
            border: 1px solid #dde3ec;
            border-radius: 3px;
            padding: 4px;
            box-sizing: border-box !important; /* Keeps padding inside the square */
        }
        .photo-caption { font-size: 10px; color: #666; margin-top: 3px; text-align: center; }
        .photo-empty { color: #999; font-style: italic; font-size: 11px; }




        /* ── Driver licence / document images ── */
        .doc-img-wrap { display: inline-block; width: 46%; margin-right: 3%; vertical-align: top; }
        .doc-img-wrap img { width: 100%; max-height: 320px; object-fit: contain; border: 1px solid #dde3ec; border-radius: 3px; background: #f5f7fa; padding: 4px; }
        .doc-img-label { font-size: 10px; font-weight: bold; color: #555; text-align: center; margin-top: 4px; }
        /* ── Insurance ── */
        .ins-img { max-width: 100%; max-height: 320px; border: 1px solid #dde3ec; border-radius: 3px; object-fit: contain; background: #f5f7fa; padding: 4px; }

        /* ── Signature ── */
        .sig-img { max-width: 250px; max-height: 180px; border: 1px solid #ccc; padding: 3px; border-radius: 3px; }
        /* ── GPS tracking route ── */
        .gps-route-img { max-width: 100%; max-height: 400px; border: 1px solid #dde3ec; border-radius: 3px; object-fit: contain; background: #f5f7fa; padding: 4px; }
        /* ── GPS summary strip ── */
        .gps-stats { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .gps-stats td { border: 1px solid #dde3ec; padding: 8px 6px; text-align: center; background: #f5f7fa; }
        .gps-stats .sv { font-size: 15px; font-weight: bold; color: #1a3a5c; }
        .gps-stats .sl { font-size: 10px; color: #777; margin-top: 2px; }
        /* ── Misc ── */
        hr.divider { border: none; border-top: 1px solid #dde3ec; margin: 14px 0; }
        .notes-box { background: #fafbfc; border: 1px solid #dde3ec; border-radius: 4px; padding: 8px 10px; line-height: 1.5; }
        .footer { border-top: 1px solid #dde3ec; margin-top: 20px; padding-top: 8px; font-size: 10px; color: #999; text-align: center; }
    </style>
</head>
<body>
<div class="page">
<?php


/* ── Status badge ── */
$status     = $testDrive->status ?? 'unknown';
$badgeClass = in_array($status, ['pending','in_progress','completed','cancelled'])
              ? 'badge-' . $status : 'badge-default';

/* ── Helpers ── */
$na = '—';
function tdVal($v)           { return !empty($v) ? htmlspecialchars((string)$v) : '—'; }

function getImage($url)
{
    /* ── CDN prefix for all user-uploaded images ── */
    $cdn = getenv('image.cdn') ?: '';
    if (empty($cdn)) {
        return null;
    }
    $ch = curl_init($cdn.$url);

    curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0'
    ]);

    $imageData = curl_exec($ch);
    curl_close($ch);

    if (!$imageData) {
        return null;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_buffer($finfo, $imageData);

    return 'data:' . $mime . ';base64,' . base64_encode($imageData);
}




/* ── Split photos by type ── */
$prePhotos = $postPhotos = [];
foreach ($photos ?? [] as $p) {
    if (($p->photo_type ?? '') === 'pre')  $prePhotos[]  = $p;
    if (($p->photo_type ?? '') === 'post') $postPhotos[] = $p;
}

/* ── Group documents by type ── */
$docsByType = [];
foreach ($documents ?? [] as $d) {
    $docsByType[$d->document_type ?? 'other'][] = $d;
}

/* ── Resolve licence image URLs (customer fields first, documents fallback) ── */
$licFront = !empty($customer->license_front) ? $customer->license_front
          : ($docsByType['driver_license'][0]->document_url ?? '');
$licBack  = !empty($customer->license_back)  ? $customer->license_back
          : ($docsByType['driver_license'][1]->document_url ?? '');

/* ── Resolve insurance URL (test drive field first, documents fallback) ── */
$insuranceUrl = !empty($testDrive->insurance_url) ? $testDrive->insurance_url
              : ($docsByType['insurance'][0]->document_url ?? '');

/* ── Resolve GPS Tracking URL (test drive field first, documents fallback) ── */
$gpsTrackingUrl = !empty($docsByType['gps'][0]->document_url ) ? $docsByType['gps'][0]->document_url :  ''  ;

/* ── GPS stats ── */
$gpsPoints      = $gpsPoints ?? [];
$totalGpsPts    = count($gpsPoints);
$gpsFirst       = $totalGpsPts > 0 ? $gpsPoints[0] : null;
$gpsLast        = $totalGpsPts > 0 ? $gpsPoints[$totalGpsPts - 1] : null;
$maxSpd = $sumSpd = $spdCount = 0;
foreach ($gpsPoints as $gp) {
    $s = (float)($gp->speed ?? 0);
    if ($s > $maxSpd) $maxSpd = $s;
    if ($s > 0) { $sumSpd += $s; $spdCount++; }
}
$avgSpd  = $spdCount > 0 ? round($sumSpd / $spdCount, 1) : 0;
$maxSpd  = round($maxSpd, 1);
?>


<!-- ══════════════════ HEADER ══════════════════ -->
<!--<div class="report-header clearfix">-->
<!--    <div class="col-half">-->
<!--        --><?php //if (!empty($logo)): ?><!--<img src="--><?php //= htmlspecialchars($logo) ?><!--" alt="Logo">--><?php //endif; ?>
<!--    </div>-->
<!--    <div class="col-half" style="text-align:right;">-->
<!--        <div class="report-title">Test Drive Report</div>-->
<!--        <div class="report-meta">-->
<!--            TD&nbsp;#--><?php //= tdVal($testDrive->id) ?><!-- &nbsp;|&nbsp;-->
<!--            <span class="badge --><?php //= $badgeClass ?><!--">--><?php //= htmlspecialchars(str_replace('_', ' ', $status)) ?><!--</span>-->
<!--        </div>-->
<!--        <div class="report-meta" style="margin-top:6px;">Generated: --><?php //= date('F j, Y  g:i a') ?><!--</div>-->
<!--    </div>-->
<!--</div>-->

<!-- ══════════════════ CUSTOMER + VEHICLE ══════════════════ -->
<div class="clearfix section">
    <div class="col-half" style="padding-right:12px;">
        <div class="section-title">Customer Information</div>
        <table class="info">
            <tr><td class="lbl">Name</td>         <td class="val"><?= $customer ? tdVal($customer->getFullName())   : $na ?></td></tr>
            <tr><td class="lbl">Phone</td>        <td class="val"><?= $customer ? tdVal($customer->phone)           : $na ?></td></tr>
            <tr><td class="lbl">Email</td>        <td class="val"><?= $customer ? tdVal($customer->email)           : $na ?></td></tr>
            <tr><td class="lbl">Address</td>      <td class="val"><?= $customer ? tdVal($customer->getFullAddress()) : $na ?></td></tr>
            <tr><td class="lbl">Licence #</td>    <td class="val"><?= $customer ? tdVal($customer->driver_license)  : $na ?></td></tr>
            <tr><td class="lbl">Lic. Expiry</td>  <td class="val"><?= $customer ? tdVal($customer->license_expiry)  : $na ?></td></tr>
        </table>
    </div>
    <div class="col-half">
        <div class="section-title">Vehicle Information</div>
        <?php if (!empty($inventory)): ?>
        <table class="info">
            <tr><td class="lbl">Vehicle</td>  <td class="val"><?= tdVal(trim($inventory->year . ' ' . $inventory->make . ' ' . $inventory->model . ' ' . $inventory->trim)) ?></td></tr>
            <tr><td class="lbl">Stock #</td>  <td class="val"><?= tdVal($inventory->stock_no) ?></td></tr>
            <tr><td class="lbl">VIN</td>      <td class="val"><?= tdVal($inventory->vin) ?></td></tr>
        </table>
        <?php else: ?><p style="color:#999;">No vehicle data available.</p><?php endif; ?>
    </div>
</div>
<hr class="divider">


<!-- ══════════════════ DRIVER LICENCE (Front + Back) ══════════════════ -->
<?php if (!empty($licFront) || !empty($licBack)): ?>
<div class="section">
    <div class="section-title">Driver Licence</div>
    <?php if (!empty($licFront)): ?>
    <div class="doc-img-wrap">
        <img src="<?= getImage($licFront) ?>" alt="Licence Front">
        <div class="doc-img-label">&#9632; FRONT</div>
    </div>
    <?php endif; ?>
    <?php if (!empty($licBack)): ?>
    <div class="doc-img-wrap">
        <img src="<?= getImage($licBack) ?>" alt="Licence Back">
        <div class="doc-img-label">&#9632; BACK</div>
    </div>
    <?php endif; ?>
</div>
<hr class="divider">
<?php endif; ?>

<!-- ══════════════════ INSURANCE ══════════════════ -->
<?php if (!empty($insuranceUrl)): ?>
<div class="section">
    <div class="section-title">Insurance</div>
    <img class="ins-img" src="<?= getImage($insuranceUrl) ?>" alt="Insurance">
</div>
<hr class="divider">
<?php endif; ?>

<!-- ══════════════════ TEST DRIVE DETAILS ══════════════════ -->
<div class="section">
    <div class="section-title">Test Drive Details</div>
    <div class="clearfix">
        <div class="col-half" style="padding-right:12px;">
            <table class="info">
                <tr><td class="lbl">Start Time</td>    <td class="val"><?= tdVal($testDrive->getStartTime()) ?></td></tr>
                <tr><td class="lbl">End Time</td>      <td class="val"><?= tdVal($testDrive->getEndTime()) ?></td></tr>
                <tr><td class="lbl">Duration</td>      <td class="val"><?= !empty($duration_minutes) ? $duration_minutes . ' min' : $na ?></td></tr>
                <tr><td class="lbl">GPS Distance</td>  <td class="val"><?= !empty($distance_km) ? number_format((float)$distance_km, 2) . ' km' : $na ?></td></tr>
            </table>
        </div>
        <div class="col-half">
            <table class="info">
                <tr><td class="lbl">Odometer Start</td> <td class="val"><?= tdVal($testDrive->odo_start) ?></td></tr>
                <tr><td class="lbl">Odometer End</td>   <td class="val"><?= tdVal($testDrive->odo_end) ?></td></tr>
                <tr><td class="lbl">Odo. Distance</td>  <td class="val"><?= tdVal($testDrive->getDistanceTraveled()) ?></td></tr>
                <tr><td class="lbl">Number Plate</td>   <td class="val"><?= tdVal($testDrive->number_plate) ?></td></tr>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($sales_person['name'])): ?>
<div class="section">
    <div class="section-title">Sales Representative</div>
    <table class="info"><tr><td class="lbl">Name</td><td class="val"><?= tdVal($sales_person['name']) ?></td></tr></table>
</div>
<?php endif; ?>

<?php if (!empty($testDrive->notes)): ?>
<div class="section">
    <div class="section-title">Notes / Issues</div>
    <div class="notes-box"><?= nl2br(htmlspecialchars($testDrive->notes)) ?></div>
</div>
<?php endif; ?>

<?php if (!empty($testDrive->customer_signature_url)): ?>
<div class="section">
    <div class="section-title">Customer Signature</div>
    <img class="sig-img" src="<?= getImage($testDrive->customer_signature_url) ?>" alt="Customer Signature">
</div>
<?php endif; ?>

<hr class="divider">

<!-- ══════════════════ PRE-DRIVE PHOTOS ══════════════════ -->
<div class="section">
    <div class="section-title">Pre-Drive Photos (<?= count($prePhotos) ?>)</div>
    <?php if (!empty($prePhotos)): ?>
        <?php foreach ($prePhotos as $p): ?>
        <div class="photo-item">
            <img src="<?= getImage($p->photo_url) ?>" alt="Pre-drive photo">
            <?php if (!empty($p->caption)): ?><div class="photo-caption"><?= htmlspecialchars($p->caption) ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?><p class="photo-empty">No pre-drive photos recorded.</p><?php endif; ?>
</div>

<!-- ══════════════════ POST-DRIVE PHOTOS ══════════════════ -->
<div class="section">
    <div class="section-title">Post-Drive Photos (<?= count($postPhotos) ?>)</div>
    <?php if (!empty($postPhotos)): ?>
        <?php foreach ($postPhotos as $p): ?>
        <div class="photo-item">
            <img src="<?= getImage($p->photo_url) ?>" alt="Post-drive photo">
            <?php if (!empty($p->caption)): ?><div class="photo-caption"><?= htmlspecialchars($p->caption) ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?><p class="photo-empty">No post-drive photos recorded.</p><?php endif; ?>
</div>

<hr class="divider">

<!-- ══════════════════ GPS TRACKING SUMMARY ══════════════════ -->
<div class="section">
    <div class="section-title">GPS Tracking Summary</div>
    <?php if ($totalGpsPts > 0): ?>
    <table class="gps-stats">
        <tr>
            <td><div class="sv"><?= $totalGpsPts ?></div><div class="sl">Total Points</div></td>
            <td><div class="sv"><?= !empty($distance_km) ? number_format((float)$distance_km, 2) . ' km' : $na ?></div><div class="sl">Distance</div></td>
            <td><div class="sv"><?= $maxSpd > 0 ? $maxSpd . ' km/h' : $na ?></div><div class="sl">Max Speed</div></td>
            <td><div class="sv"><?= $avgSpd > 0 ? $avgSpd . ' km/h' : $na ?></div><div class="sl">Avg Speed</div></td>
            <td><div class="sv" style="font-size:11px;"><?= $gpsFirst ? tdVal($gpsFirst->recorded_at) : $na ?></div><div class="sl">First Point</div></td>
            <td><div class="sv" style="font-size:11px;"><?= $gpsLast  ? tdVal($gpsLast->recorded_at)  : $na ?></div><div class="sl">Last Point</div></td>
        </tr>
    </table>
<!--    --><?php
//    /* Show first 5 + separator + last 5 when > 10 points */
//    if ($totalGpsPts <= 10) {
//        $sample = $gpsPoints;
//        $omitted = 0;
//    } else {
//        $sample  = array_merge(array_slice($gpsPoints, 0, 5), [null], array_slice($gpsPoints, -5));
//        $omitted = $totalGpsPts - 10;
//    }
//    ?>
<!--    <table class="data-table">-->
<!--        <thead>-->
<!--            <tr>-->
<!--                <th>#</th><th>Recorded At</th><th>Latitude</th><th>Longitude</th>-->
<!--                <th>Speed (km/h)</th><th>Accuracy (m)</th><th>Heading (°)</th>-->
<!--            </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        --><?php //$n = 1; foreach ($sample as $gp): ?>
<!--            --><?php //if ($gp === null): ?>
<!--            <tr><td colspan="7" style="text-align:center;color:#999;font-style:italic;">&hellip; --><?php //= $omitted ?><!-- more points &hellip;</td></tr>-->
<!--            --><?php //continue; endif; ?>
<!--            <tr>-->
<!--                <td>--><?php //= $n++ ?><!--</td>-->
<!--                <td>--><?php //= tdVal($gp->recorded_at ?? '') ?><!--</td>-->
<!--                <td>--><?php //= tdVal(number_format((float)($gp->latitude  ?? 0), 6)) ?><!--</td>-->
<!--                <td>--><?php //= tdVal(number_format((float)($gp->longitude ?? 0), 6)) ?><!--</td>-->
<!--                <td>--><?php //= !empty($gp->speed)    ? number_format((float)$gp->speed,    1) : $na ?><!--</td>-->
<!--                <td>--><?php //= !empty($gp->accuracy) ? number_format((float)$gp->accuracy, 1) : $na ?><!--</td>-->
<!--                <td>--><?php //= !empty($gp->heading)  ? number_format((float)$gp->heading,  1) : $na ?><!--</td>-->
<!--            </tr>-->
<!--        --><?php //endforeach; ?>
<!--        </tbody>-->
<!--    </table>-->
    <?php else: ?>
    <p style="color:#999;font-style:italic;">No GPS points recorded for this test drive.</p>
    <?php endif; ?>
</div>

<hr class="divider">

<!-- ══════════════════ AUDIT TRAIL ══════════════════ -->
<?php //if (!empty($events) && is_array($events)): ?>
<!--<div class="section">-->
<!--    <div class="section-title">Audit Trail</div>-->
<!--    <table class="data-table">-->
<!--        <thead>-->
<!--            <tr><th style="width:22%;">Event</th><th style="width:22%;">Date / Time</th><th>Details</th></tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--            --><?php //foreach ($events as $event): ?>
<!--            <tr>-->
<!--                <td>--><?php //= tdVal(str_replace('_', ' ', $event->event_type ?? '')) ?><!--</td>-->
<!--                <td>--><?php //= tdVal($event->created_at ?? '') ?><!--</td>-->
<!--                <td>--><?php //= tdVal($event->event_data ?? '') ?><!--</td>-->
<!--            </tr>-->
<!--            --><?php //endforeach; ?>
<!--        </tbody>-->
<!--    </table>-->
<!--</div>-->
<?php //endif; ?>

    <!-- ══════════════════ GPS TRACKING ROUTE ══════════════════ -->
    <?php if (!empty($gpsTrackingUrl)): ?>
        <div class="section">
            <div class="section-title">GPS Tracking Route</div>
            <img class="gps-route-img" src="<?= getImage($gpsTrackingUrl) ?>" alt="Insurance">
        </div>
    <?php endif; ?>


<!-- ══════════════════ FOOTER ══════════════════ -->
<div class="footer">
    Test Drive #<?= tdVal($testDrive->id) ?> &nbsp;&bull;&nbsp;
    Generated on <?= date('Y-m-d H:i:s') ?> &nbsp;&bull;&nbsp; Confidential
</div>

</div><!-- .page -->
</body>
</html>