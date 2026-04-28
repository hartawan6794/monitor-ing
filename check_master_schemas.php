<?php
$handle = fopen('c:\laragon\www\montor-ing\backup\EPPYS_10-04-2026.sql', 'r');
$tables = ['productgroup', 'supplier', 'factories', 'productbrand'];
$found = [];

while (($line = fgets($handle)) !== false) {
    foreach ($tables as $t) {
        if (!isset($found[$t]) && strpos($line, "CREATE TABLE `$t`") !== false) {
            echo "--- SCHEMA FOR $t ---\n";
            echo $line;
            $found[$t] = true;
            while (($innerLine = fgets($handle)) !== false) {
                echo $innerLine;
                if (strpos($innerLine, ';') !== false && strpos($innerLine, 'ENGINE=') !== false) {
                    break;
                }
            }
        }
    }
    if (count($found) == count($tables)) break;
}
fclose($handle);
