<!-- app/Views/popular_tags.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Tags</title>
</head>
<body>
    <h1>Popular Tags</h1>
    <table border="1">
        <tr>
            <th>Tag Name</th>
            <th>Subscriber Count</th>
        </tr>
        <?php foreach ($popularTags as $tag): ?>
            <tr>
                <td><?= $tag['tag_name'] ?></td>
                <td><?= $tag['subscriber_count'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
