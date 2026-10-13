<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Inspection Report | <?php echo $inventory['year'] . ' ' . $inventory['make'] . ' ' . $inventory['model'] . ' ' . $inventory['trim'];  ?></title>
    <!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="fullscreen image, grid layout, flexbox grid, transition" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Open Sans', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: auto;
        }

        .main-head-b h2 {
            margin: 0px;
            padding-top: 10px;
            font-size: 35px;
            font-weight: 700;
        }

        .main-head-b h4 {
            margin: 0px;
            padding-top: 10px;
            font-size: 25px;
            font-weight: 700;
        }

        .main-head-b p {
            font-size: 14px;
            line-height: 25px;
            margin: 0px;
        }

        .detail-content-box {
            display: block;
            padding-top: 25px;
        }

        ul.list-box {
            padding: 0px;
        }

        ul.list-box li {
            display: inline-block;
            padding: 15px 0px;
            width: 100%;
            float: left;
            margin-bottom: 5px;
            border-bottom: 1px solid #f4f8fa;
        }

        ul.list-box li p {
            margin: 0px;
            width: auto;
            float: right;
            color: #757989;
            font-size: 14px;
            position: relative;
            top: -2px;
        }

        ul.list-box li p img {
            width: 20px;
            position: relative;
            top: 4px;
            margin: 0px 2px 0px 10px;
        }

        ul.list-box li p a {
            font-size: 13px;
            color: #00aed9;
        }

        .box-w-100 {
            width: 100%;
        }

        .sp-list {
            padding: 0px 20px;
        }

        ul.list-box li strong {
            font-size: 16px;
            color: #183558;
        }

        ul.list-box li ul.sp-list li {
            display: inline-block;
            padding: 0px;
            float: left;
            margin-bottom: 0px;
            border: none;
            font-size: 13px;
        }

        ul.list-box li ul.sp-list li.box-w-60 strong {
            display: block;
            padding-bottom: 5px;
        }

        ul.list-box li ul.sp-list li.box-w-15 {
            width: 10%;
        }

        ul.list-box li ul.sp-list li.box-w-25 {
            width: 20%;
            text-align: right;
            font-weight: 700;
        }

        ul.list-box li ul.sp-list li.box-w-60 {
            width: 70%;
        }

        ul.list-box li svg {
            width: 20px;
            position: relative;
            top: 5px;
            margin-left: 5px;
            fill: #f1c40f;
        }
    </style>
</head>


<!--  BODY START  -->

<body>
    <div class="container">
        <div class="main-head-b">
            <h2><?php echo $inspection['title']; ?></h2>
            <h4><?php echo $inventory['year'] . ' ' . $inventory['make'] . ' ' . $inventory['model'] . ' ' . $inventory['trim'];  ?></h4>
            <?php if ($inventory['vindisplay'] == '1') { ?>
                <div class="box-w-100 right-list-block">
                    <p><strong>VIN: </strong><?php echo $inventory['vin']; ?></p>
                </div>
            <?php } ?>
            <p><?php echo $inspection['description']; ?></p>
        </div>
        <table class="table col-12">

            <?php $index = 1;
            foreach ($inspection['sections'] as $section) { ?>

                <tr class="thead-white">
                    <th scope="col" colspan="2" style="border:1px solid #f4f8fa !important;background:#f4f8fa;"><strong style="font-size:20px;"><?php echo $section['name']; ?></strong></th>
                    <th scope="col" style="width: 200px;text-align:center;border:1px solid #f4f8fa !important;background:#f4f8fa;">
                    </th>
                </tr>

                <?php foreach ($section['list'] as $inspection_point) { ?>
                    <?php if ($inspection_point["visibility"] == "1") { ?>
                        <tr>
                            <td class="p-50"><strong><?php echo $index;
                                                $index++; ?></strong></td>
                            <td class="p-50">
                                <h6 class="col-12"><span><strong><?php echo $inspection_point['title']; ?></strong></span></h6>
                                <div class="col-12" style="font-size: 0.8em; font-style: italic;">
                                    <span><?php echo $inspection_point['description']; ?></span>
                                </div>
                            </td>
                            <td class="p-50 text-center">
                                <?php if ($inspection_point['value'] == "Passed") {  ?>
                                    <i title="Passed" class="bx bxs-check-circle pr-1 bx-sm text-success bx-md" style="cursor: pointer;"></i>
                                <?php } else if ($inspection_point['value'] == "Imperfections") { ?>
                                    <i title="Imperfections" class="bx bxs-error-circle bx-md pr-1 text-warning" style="cursor: pointer;"></i>
                                <?php } else { ?>
                                    <b> N/A </b>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            <?php } ?>

        </table>
    </div>
</body>
<!---- BODY END ---->

</html>