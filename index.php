<?php
require_once 'db_connect.php'; // Подключаем файл с БД

// Получаем список пользователей с транзакциями
$query = "SELECT DISTINCT users.id, users.name FROM users
          JOIN user_accounts ON users.id = user_accounts.user_id
          JOIN transactions ON user_accounts.account_number IN (transactions.from_account, transactions.to_account)";
$users = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Balance</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Select User</h2>
    <select id="userSelect">
        <option value="">-- Select User --</option>
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Month</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody id="balanceTable"></tbody>
    </table>
    <script>
        $(document).ready(function () {
            $('#userSelect').change(function () {
                let userId = $(this).val();
                if (userId) {
                    $.ajax({
                        url: 'fetch_balance.php',
                        method: 'POST',
                        data: { user_id: userId },
                        success: function (response) {
                            $('#balanceTable').html(response);
                        }
                    });
                } else {
                    $('#balanceTable').html('');
                }
            });
        });
    </script>
</body>
</html>
