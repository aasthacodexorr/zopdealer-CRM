<style>
    @import url('http://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap');

    .head-set-left {
        width: 30%;
        float: left;
        margin-bottom: 25px;
    }

    .head-set-right {
        width: 70%;
        float: left;
    }

    .head-set-list {
        overflow: hidden;
        display: block;
    }

    .head-set {
        max-width: 750px;
        margin: auto;
    }

    .head-set-left img {
        margin: auto;
        max-width: 200px;
        margin-top: 15px;
    }

    .price-title h2 {
        font-size: 36px;
        text-transform: uppercase;
        text-align: right;
        margin-bottom: 0px;
        font-family: 'Poppins', sans-serif;
        margin-top: 20px;
    }

    .price-value h3 {
        font-size: 26px;
        font-weight: 400;
        text-align: right;
        margin: 0px;
        font-family: 'Poppins', sans-serif;
        line-height: 85px;
    }

    .price-value h3 span {
        font-size: 75px;
        font-weight: 700;
        position: relative;
        top: 20px;
    }

    .offer-value h4 {
        text-align: right;
        font-size: 28px;
        font-weight: 400;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 0px;
        margin-top: 20px;
    }

    p.customer-content {
        font-size: 22px;
        font-family: 'Poppins', sans-serif;
        margin-bottom: 0px;
        margin-top: 15px;
    }

    p.customer-discpation {
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
    }

    .status.invalid {
        color: #ff5b5c;
    }

    .status.valid {
        color: #5cb85c;
    }

    .status.redeemed {
        color: #f0ad4e;
    }
</style>
<?php $fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY); ?>
<div class="head-set">
    <div class="head-set-list">
        <div class="head-set-left">
            <div class="head-logo-left">
                <img src="data:image/png;base64,<?php echo $business_logo; ?>">
                <br>
                <?php echo $qr_code_image; ?>
            </div>
        </div>
        <div class="head-set-right">
            <div class="price-title">
                <h2><?php echo $title; ?></h2>
            </div>
            <div class="price-value">
                <h3>VALUE: <span><?php echo $fmt->format($value); ?></span></h3>
            </div>
            <div class="offer-value">
                <h4>Offer Expires: <?php echo $expiry_date; ?></h4>
            </div>
            <div class="offer-value">
                <h4>Status:  <span style="font-weight: 800;" class="status text-bold <?php echo $status; ?>"> <?php echo $status; ?> </span></h4>
            </div>
        </div>
    </div>
    <div class="customer-detail">
        <p class="customer-content"> Customer Name: <strong style="text-transform: uppercase;"><?php echo $first_name . ' ' . $last_name; ?></strong></p>
        <p class="customer-content">Vehicle: <strong><?php echo $year; ?> <?php echo $make; ?> <?php echo $model; ?> - <span style="text-transform: uppercase;"><?php echo $vin; ?></span></strong></p>
        <p class="customer-discpation"><?php echo $disclosure; ?></p>
    </div>
</div>