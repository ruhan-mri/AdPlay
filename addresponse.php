<?php

// echo "I am here in response";

$campaigns = require __DIR__ . '/addcampains.php';

$id = $_GET['id'] ?? null;
$bidRequest = isset($_GET['bidRequest']) ? json_decode($_GET['bidRequest'], true) : null;
$device = isset($_GET['device']) ? json_decode($_GET['device'], true) : null;
$bidfloor = $_GET['bidfloor'] ?? null;


if ($id && $bidRequest && $device && $bidfloor !== null) {

    // echo "ID: " . htmlspecialchars($id);
    // echo "Bid Request: " . htmlspecialchars(print_r($bidRequest, true));
    // echo "Device: " . htmlspecialchars(print_r($device, true));
    // echo "Bid Floor: " . htmlspecialchars($bidfloor);




    $eligibleCampaigns = array_filter($campaigns, function ($campaign) use ($device) {
        $campaignCountry = strtoupper($campaign['country']);
        $deviceCountry = strtoupper($device['geo']['country']); //uppercase

        //list to lowercase and split into array
        $campaignOSList = array_map('strtolower', explode(',', $campaign['hs_os']));

        $deviceOS = strtolower($device['os']);

        return $campaignCountry === $deviceCountry && in_array($deviceOS, $campaignOSList);
    });

    // no campaigns
    if (empty($eligibleCampaigns)) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "No matching campaign found"
        ]);
        exit;
    }

    //best matching
    $validCampaign = null;
    foreach ($eligibleCampaigns as $campaign) {
        if ($campaign['price'] >= $bidfloor) {
            $validCampaign = $campaign; //first valid data
            break;
        }
    }


    if ($validCampaign) {
        $response = [
            "id" => $bidRequest['id'],
            "seatbid" => [
                [
                    "bid" => [
                        [
                            "id" => $validCampaign['code'],
                            "impid" => $bidRequest['imp'][0]['id'],
                            "price" => $validCampaign['price'],
                            "adid" => $validCampaign['creative_id'],
                            "nurl" => $validCampaign['url'],
                            "adm" => [
                                "creative_type" => $validCampaign['creative_type'],
                                "image_url" => $validCampaign['image_url'],
                                "landing_page_url" => $validCampaign['url'],
                                "campaignname" => $validCampaign['campaignname'],
                                "advertiser" => $validCampaign['advertiser'],
                                "operator" => $validCampaign['operator'],
                            ],
                        ]
                    ]
                ]
            ]
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    } else {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "No valid campaign found with bid price above the floor"
        ]);
    }
} else {
    echo "Required data is missing in the request.";
}
