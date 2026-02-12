<?php
$log = "URI: " . $_SERVER['REQUEST_URI'] . "\n";
$log .= "Script: " . $_SERVER['SCRIPT_NAME'] . "\n";
$log .= "Parsed URL: " . $url . "\n";
$log .= "Segments: " . print_r($segments, true) . "\n";
file_put_contents(__DIR__ . '/logs/router_debug.txt', $log, FILE_APPEND);
