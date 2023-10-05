<?php
$serial = $_GET["serial"];
$date = date("Y-m-d H:i:s");
$data = sprintf("On %s we got this serial: %s\n", $date, $serial);
file_put_contents("data.txt", $data, FILE_APPEND);
# Respond to the client
printf("Serial %s saved", $serial);
