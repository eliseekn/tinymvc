<html lang="en">

<head>
    <style>
        table {
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: .5em;
        }

        thead > tr > th {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <table>
        <?php if (!empty($headers)) : ?>

            <thead>
                <tr>
                    <th align="left">#</th>

                    <?php foreach ($headers as $header) : ?>

                        <th align="left"><?= $header ?></th>

                    <?php endforeach ?>
                </tr>
            </thead>

        <?php endif ?>

        <tbody>
            <?php foreach ($data as $key => $value) : ?>

                <tr>
                    <td><?= $key + 1 ?></td>

                    <?php foreach ($value as $column) : ?>

                        <td><?= $column ?></td>

                    <?php endforeach ?>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>

</body>

</html>