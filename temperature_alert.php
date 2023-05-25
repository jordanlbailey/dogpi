<?php

// read the temperature alert range from temp_alert.txt
$temp_alert = file('/var/www/html/temp_alert.txt')[0];
$temp_alert = explode(',', trim($temp_alert));
$temp_high = floatval($temp_alert[0]);
$temp_low = floatval($temp_alert[1]);

// read the current temperature from data.txt
$current_temp = file('/var/www/html/data.txt')[0];
$current_temp = explode(',', trim($current_temp))[0];
$current_temp = floatval($current_temp);

if (intval($temp_alert[0]) == intval(0) && intval($temp_alert[1]) == intval(0))
{
echo "Alerts Disabled\n";
$disable = true;
}
else
{$disable = false;}

// check if the current temperature is outside the alert range
if (($current_temp > $temp_high || $current_temp < $temp_low) && ($disable == false)) {
echo "Alert Triggered\n";

    // send an alert using ntfy.sh
    $data = 'Temperature alert! Current temperature is ' . number_format($current_temp, 2) . 'F.';
    $headers = array(
        'Title: DogPi2 Temperature alerts',
        'Priority: urgent',
        'Tags: warning',
        'Content-Type: text/plain'
    );


    $payload = array(
        'data' => $data,
        'headers' => $headers
    );
//    $ch = curl_init();
//    $curl_setopt($ch, CURLOPT_URL, 'https://ntfy.sh/Van_temperature');
//    $curl_setopt($ch, CURLOPT_POST, true);
//    $curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
//    $curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//    $curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $result = curl_exec($ch);
//    $curl_close($ch);

file_get_contents('https://ntfy.sh/van_temperature', false, stream_context_create([
    'http' => [
        'method' => 'POST', // PUT also works
        'header' => $headers,
        'content' => $data
    ]
]));
}
?>
