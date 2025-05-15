<?php
include('connect.php'); // Connect to the database

// 2. Fetch all payment records
$result = $conn->query("SELECT * FROM payments ORDER BY payment_date DESC");

// 3. Display the records
echo "<h2>All Payments</h2>";
echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<tr>
        <th>ID</th>
        <th>Card Number</th>
        <th>Expiry Date</th>
        <th>CV Code</th>
        <th>Card Owner</th>
        <th>Payment Date</th>
      </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['card_number']}</td>
            <td>{$row['expiry_date']}</td>
            <td>{$row['cv_code']}</td>
            <td>{$row['card_owner']}</td>
            <td>{$row['payment_date']}</td>
          </tr>";
}

echo "</table>";

// 4. Close connection
$conn->close();
?>
