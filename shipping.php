<?php

$apiKey = "EP-4c3yL6nza";

$url = "https://app.easyparcel.com/api/easyparcel/quotation";

$data = [
    "api" => $apiKey,
    "bulk" => [
        [
            "pick_code" => "43000",
            "send_code" => "50000",
            "weight" => "1",
        ]
    ]
];

$options = [
    "http" => [
        "header"  => "Content-type: application/json",
        "method"  => "POST",
        "content" => json_encode($data),
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;
?>