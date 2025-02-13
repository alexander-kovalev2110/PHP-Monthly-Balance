<?php
require_once 'db_connect.php';   // Connecting the DB

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = (int)$_POST['user_id'];

    $query = "SELECT 
                DATE_FORMAT(t.date, '%Y-%m') AS month, 
                SUM(CASE WHEN t.to_account = ua.account_number THEN t.amount ELSE 0 END) -
                SUM(CASE WHEN t.from_account = ua.account_number THEN t.amount ELSE 0 END) AS balance
              FROM transactions t
              JOIN user_accounts ua ON ua.account_number IN (t.from_account, t.to_account)
              WHERE ua.user_id = ?
              GROUP BY month
              ORDER BY month DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $balances = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($balances as $row) {
        echo "<tr><td>" . htmlspecialchars($row['month']) . "</td><td>" . htmlspecialchars($row['balance']) . "</td></tr>";
    }
}
?>
