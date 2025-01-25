<?php

require_once '../../csrest_journey_emails.php';

$auth = array(
    'access_token' => 'your access token',
    'refresh_token' => 'your refresh token');
$wrap = new CS_REST_JourneyEmails('Email ID to get recipients for', $auth);

//$result = $wrap->get_journey_recipients(date('Y-m-d', strtotime('-30 days')), page, page size, order direction);
$result = $wrap->get_journey_recipients('Get recipients since', 1, 50, 'email', 'asc');

echo "Result of GET /api/v3.2/journeys/email/{id}/recipients\n<br />";
if($result->was_successful()) {
    echo "Got recipients\n<br /><pre>";
    var_dump($result->response);
} else {
    echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
    var_dump($result->response);
}
echo '</pre>';