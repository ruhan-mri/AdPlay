# Real-Time Bidding (RTB) Campaign Selection and Response Generation

This PHP project handles bid requests and generates appropriate banner campaign responses for Real-Time Bidding (RTB) scenarios. The script parses incoming bid requests, selects the most suitable campaign based on the parameters, and generates a valid RTB response.

## Project Structure

1. **addcampaigns.php**: Contains the logic to add campaigns to the system. Campaigns are stored in an array and used to compare with incoming bid requests.
2. **addrequest.php**: Parses incoming bid requests, extracts relevant parameters (device information, geo location, bid floor, etc.), and validates the request.
3. **addresponse.php**: Selects the best campaign based on the bid request parameters and generates an RTB-compliant response with campaign details.

## Requirements

### PHP Version
Ensure you have PHP 7.4 or higher installed on your system.

### Dependencies
No external dependencies are required for this project. All functionality is implemented in pure PHP.

## Features

- **Bid Request Handling**: Parses and validates incoming bid request JSON. Ensures parameters such as device, geo-location, and bid floor are properly handled.
  
- **Campaign Selection**: 
  - Compares bid request parameters with the available campaigns.
  - Selects the most suitable campaign based on device compatibility, geographical targeting, and bid floor.
  - If multiple campaigns match, selects the one with the highest bid price.

- **Banner Campaign Response**: 
  - Generates a valid RTB response in JSON format containing the selected campaign details like campaign name, advertiser, creative type, image URL, and landing page URL.
  - Includes necessary RTB fields such as bid price, campaign ID, and creative ID.

## How to Use

1. Clone or download this repository to your local server.

2. Place the PHP files (`addcampaigns.php`, `addrequest.php`, `addresponse.php`) in your server's public directory.

3. **Add Campaigns**: Use `addcampaigns.php` to populate the available campaigns. This file contains an array of campaign data to be used for selection.

4. **Send Bid Request**: Send a bid request JSON to the `addrequest.php` endpoint to simulate incoming bid requests. This file will parse and validate the request.

5. **Generate Response**: After validating and processing the bid request, `addresponse.php` will generate a valid RTB response with campaign details.

## Postman Setup

### **Send Bid Request**
- URL: `http://localhost/addrequest.php`
- Method: `POST`
- **Description**: This endpoint handles incoming bid requests. Send a POST request with the bid request JSON.

