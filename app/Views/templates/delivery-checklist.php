<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery Checklist</title>
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
            padding: 0.75rem 1rem;
            vertical-align: top;
            border-top: 1px solid #dfe3e7;
            border-top-color: rgb(223, 227, 231);
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
    </style>
</head>

<body>
    <div class="container">
        <h1 class="item"><?php echo $title; ?></h1>
        <p class="item"><?php echo $description; ?></p>
        <table class="item table" style="width: 100%;">
            <tbody>
                <?php
                foreach ($sections as $section) { ?>
                    <tr class="thead-dark">
                        <th scope="col" colspan="4">
                            <?php echo $section['name']; ?>
                        </th>
                    </tr>
                    <?php $count = 0; ?>
                    <?php foreach ($section['list'] as $item) { ?>
                        <tr>
                            <td class="p-50 text-center p-50">
                                <?php echo $item['value'] === 'Yes' ? "&#x2611;" : "&#x25A2"; ?>

                            </td>
                            <td class="p-50"><?php echo ++$count; ?></td>
                            <td class="p-50">
                                <span class="heading"><?php echo $item['title']; ?></span>
                                <?php if (!empty($item['description'])) { ?>
                                    <div style="font-size: 0.8em; font-style: italic;">
                                        <span><?php echo $item['description']; ?></span>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php  } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>