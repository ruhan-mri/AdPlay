<?php

//prevent injection attacks
function sanitize_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// read and parse
$input = file_get_contents('php://input');

try {
    $bidRequest = json_decode($input, true, 512, JSON_BIGINT_AS_STRING);

    // validation
    if (empty($bidRequest['id']) || empty($bidRequest['imp'][0]['banner']['format']) || empty($bidRequest['device'])) {
        throw new InvalidArgumentException("Missing required fields in bid request");
    }

    $device = [
        'make' => sanitize_input($bidRequest['device']['make'] ?? 'Unknown'),
        'model' => sanitize_input($bidRequest['device']['model'] ?? 'Unknown'),
        'os' => sanitize_input($bidRequest['device']['os'] ?? 'Unknown'),
        'os_version' => sanitize_input($bidRequest['device']['osv'] ?? 'Unknown'),
        'geo' => [
            'country' => sanitize_input($bidRequest['device']['geo']['country'] ?? 'Unknown'),
            'city' => sanitize_input($bidRequest['device']['geo']['city'] ?? 'Unknown'),
            'lat' => sanitize_input($bidRequest['device']['geo']['lat'] ?? 'N/A'),
            'lon' => sanitize_input($bidRequest['device']['geo']['lon'] ?? 'N/A')
        ]
    ];

    $bidFloor = $bidRequest['imp'][0]['bidfloor'] ?? 0;

    // echo "I am here in request";

    // Return the parsed request data and sanitized information for further processing
    // return [
    //     'bidRequest' => $bidRequest,
    //     'device' => $device,
    //     'bidFloor' => $bidFloor
    // ];


    $url = 'addresponse.php?' .
        'id=' . urlencode($bidRequest['id']) .
        '&bidRequest=' . urlencode(json_encode($bidRequest)) .
        '&device=' . urlencode(json_encode($device)) .
        '&bidfloor=' . urlencode($bidFloor);

    header('Location: ' . $url);
    exit;
} catch (JsonException $je) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON provided: " . $je->getMessage()
    ]);
    exit;
} catch (InvalidArgumentException $iae) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => $iae->getMessage()
    ]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "An unexpected error occurred: " . $e->getMessage()
    ]);
    exit;
}
