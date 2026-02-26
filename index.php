<?php
include 'db.php';

$sql = "SELECT customer.name, books.title, orders.order_date
FROM orders
JOIN customer ON orders.customer_id = customer.customer_id
JOIN books ON orders.book_id = books.book_id";

$result = $conn->query($sql);
?>

<table border="1">
<tr>
<th>Customer Name</th>
<th>Book Title</th>
<th>Order Date</th>
<th>Shipping Cost</th>
<th>Exchange Rate</th>
</tr>

<?php
while($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>".$row['name']."</td>
    <td>".$row['title']."</td>
    <td>".$row['order_date']."</td>
    <td>RM 8.00</td>
    <td>4.70</td>
    </tr>";
}
?>
</table>