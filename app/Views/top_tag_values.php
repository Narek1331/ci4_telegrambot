<!-- app/Views/top_tag_values.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Tag Values</title>
</head>
<body>
    <h1>Top Tag Values</h1>
    <table border="1">
        <tr>
            <th>Tag Name</th>
            <th>Tag Value</th>
            <th>Subscriber Count</th>
        </tr>
        <?php foreach ($topTagValues as $tagValue): ?>
            <tr>
                <td><?= $tagValue['tag_name'] ?></td>
                <td><?= $tagValue['tag_value'] ?></td>
                <td><?= $tagValue['subscriber_count'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
