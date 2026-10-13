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
    <title>Vehicle Inspection Report | <?php echo $inventory['year'] . ' ' . $inventory['make'] . ' ' . $inventory['model'] . ' ' . $inventory['trim'];  ?></title>
</head>

<body>
    <div class="container">
        <div class="row">
            <h1 class="col-12 text-center"><?php echo $inspection['title']; ?></h1>
            <div class="row">
                <?php foreach ($inspection['sections'] as $section) {
                    $imperfection_count = 0; ?>
                    <div class="col-6">
                        <div class="row border-bottom" style="min-height:5em;">
                            <h3 class="col-6"><?php echo $section['name']; ?></h3>
                            <div class="col-6 text-right">
                            <?php foreach ($section['list'] as $inspection_point) {
                                $imperfection_count =
                                    $inspection_point["visibility"] == "1" && $inspection_point['value'] == "Imperfections" ?
                                    ++$imperfection_count : $imperfection_count;
                            } ?>
                            <?php if ($imperfection_count > 0) { ?>
                                <span style="vertical-align:super"> <?php echo $imperfection_count; ?> Imperfections</span> <i title="Imperfections" class="bx bxs-error-circle bx-md pr-1 text-warning pl-1 pr-1" style="cursor: pointer;"></i>
                            <?php } else { ?>
                                <span style="vertical-align:super">Passed</span> <i title="Passed" class="bx bxs-check-circle pr-1 bx-sm text-success bx-md pl-1 pr-1" style="cursor: pointer;"></i>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php   } ?>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>