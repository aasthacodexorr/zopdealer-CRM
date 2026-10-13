<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
    <title>Vehicle Inspection Report | <?php echo $inventory['year'] .' '. $inventory['make'] .' '. $inventory['model'] .' ' . $inventory['trim'];  ?></title>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1 class="col-12"><?php echo $inspection['title']; ?></h1>
            <h3 class="col-12"><i class='bx bxs-car pr-1'></i> <?php echo $inventory['year'] .' '. $inventory['make'] .' '. $inventory['model'] .' ' . $inventory['trim'];  ?></h3>
            <?php if($inventory['vindisplay'] == '1') { ?>
                <h5 class="col-12">VIN : <?php echo $inventory['vin']; ?></h5>
            <?php } ?>
            <div class="col-12 mb-1"><?php echo $inspection['description']; ?></div>
            <table class="table col-12">

            <?php $index = 1; foreach($inspection['sections'] as $section ){ ?>
            
            <tr class="thead-light">
                <th scope="col" colspan="2"><?php echo $section['name']; ?></th>
                <th scope="col" style="width: 200px;text-align:center;">
                <i title="Passed" class="bx bxs-check-circle text-success pr-1 bx-sm"></i>
                <i title="Imperfections" class="bx bx-error-circle text-warning bx-sm pr-1"></i>
                </th>
            </tr>

                <?php foreach($section['list'] as $inspection_point ) { ?>
                    <?php if($inspection_point["visibility"] == "1") { ?>
                    <tr>
                        <td class="p-50"><?php echo $index ; $index++; ?></td> 
                        <td class="p-50">
                            <h6 class="col-12"><span><?php echo $inspection_point['title']; ?></span></h6>
                            <div class="col-12" style="font-size: 0.8em; font-style: italic;">
                            <span><?php echo $inspection_point['description']; ?></span>
                            </div>
                        </td>
                        <td class="p-50 text-center">
                            <?php if($inspection_point['value'] == "Passed") {  ?>
                            <i title="Passed" class="bx bxs-check-circle pr-1 bx-sm text-success bx-md" style="cursor: pointer;"></i>
                            <?php } else if($inspection_point['value'] == "Imperfections") { ?>
                            <i title="Imperfections" class="bx bxs-error-circle bx-sm pr-1 text-warning" style="cursor: pointer;"></i>
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
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>