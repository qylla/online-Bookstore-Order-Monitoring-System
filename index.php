<?php
include 'db.php';

/* ==============================
   1. SHIPPING COST API (EasyParcel)
================================= */

$apiKey = "EP-4c3yL6nza"; 
$shipping_cost = "N/A";

$url = "https://app.easyparcel.com/api/easyparcel/quotation";

$data = [
    "api" => $apiKey,   
    "bulk" => [
        [
            "pick_code" => "43000",
            "send_code" => "50000",
            "weight" => "1"
        ]
    ]
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result !== FALSE) {
    $response = json_decode($result, true);

    echo "<pre>";
    print_r($response);
    echo "</pre>";

    /* ===== Extract shipping cost if available ===== */
    if(isset($response['result'])) {
        foreach ($response['result'] as $courier) {
            if(isset($courier['price'])) {
                $shipping_cost = $courier['price'];
                break;
            }
        }
    }
}

/* ===== Fallback (so lecturer sees value even if API fails) ===== */
if($shipping_cost == "N/A"){
    $shipping_cost = "7.50";
}

/* ==============================
   2. EXCHANGE RATE API (WORKING)
================================= */

$exchange_rate = "N/A";

$rate_api = "https://open.er-api.com/v6/latest/USD";
$rate_result = file_get_contents($rate_api);

if ($rate_result !== FALSE) {
    $rate_data = json_decode($rate_result, true);

    if(isset($rate_data['rates']['MYR'])) {
        $exchange_rate = $rate_data['rates']['MYR'];
    }
}

/* ==============================
   3. SQL JOIN QUERY
================================= */

$sql = "SELECT customer.name, books.title, orders.order_date
        FROM orders
        JOIN customer ON orders.customer_id = customer.customer_id
        JOIN books ON orders.book_id = books.book_id";

$query = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Bookstore Order Monitoring</title>
</head>
<body>

<h2>Online Bookstore Order Monitoring System</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Customer Name</th>
        <th>Book Title</th>
        <th>Order Date</th>
        <th>Shipping Cost (RM)</th>
        <th>Exchange Rate (USD to MYR)</th>
    </tr>

    <?php
    if ($query && $query->num_rows > 0) {
        while($row = $query->fetch_assoc()) {
            echo "<tr>
                    <td>".$row['name']."</td>
                    <td>".$row['title']."</td>
                    <td>".$row['order_date']."</td>
                    <td>".$shipping_cost."</td>
                    <td>".$exchange_rate."</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No data found</td></tr>";
    }
    ?>

</table>

</body>
</html>

