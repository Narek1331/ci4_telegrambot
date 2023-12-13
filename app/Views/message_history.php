<!-- app/Views/message_history.php -->

<h1>История сообщений</h1>

<?php foreach ($messages as $message): ?>
    <div>
        <strong><?= $message['username']; ?>:</strong>
        <div>Входящее сообщение: <?= $message['incoming_message']; ?></div>
        <div>Исходящее сообщение: <?= $message['outgoing_message']; ?></div>
    </div>
<?php endforeach; ?>
