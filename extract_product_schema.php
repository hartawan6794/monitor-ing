<?php
$handle = fopen('c:\laragon\www\montor-ing\backup\EPPYS_10-04-2026.sql', 'r');
$found = false;
while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'CREATE TABLE `product`') !== false) {
        $found = true;
    }
    if ($found) {
        echo $line;
        if (strpos($line, ';') !== false && strpos($line, 'ENGINE=') !== false) {
            break;
        }
    }
}
fclose($handle);
