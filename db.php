<?php
$conn = new mysqli("localhost", "root", "", "online_bookstore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> 