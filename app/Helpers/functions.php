<?php

if (! function_exists('decryptXor')) {
    /**
     * Fungsi XOR Decrypt yang sama dengan Python
     *
     * @param string $hex_str Format Hex string
     * @param string $key Kunci dekripsi rahasia
     * @return string|null Plain text hasil dekripsi
     */
    function decryptXor($hex_str, $key = "Innaddiina ")
    {
        try {
            $data = hex2bin($hex_str);
            $result = "";
            $keyLen = strlen($key);
            for ($i = 0; $i < strlen($data); $i++) {
                $b = ord($data[$i]);
                $result .= chr($b ^ ord($key[$i % $keyLen]));
            }
            return $result;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (! function_exists('encryptXor')) {
    /**
     * Fungsi XOR Encrypt yang sama dengan Python
     *
     * @param string $plain_text Teks asli yang akan dienkripsi
     * @param string $key Kunci enkripsi rahasia
     * @return string|null Hex string hasil enkripsi
     */
    function encryptXor($plain_text, $key = "Innaddiina ")
    {
        try {
            $result = "";
            $keyLen = strlen($key);
            for ($i = 0; $i < strlen($plain_text); $i++) {
                $b = ord($plain_text[$i]);
                $result .= chr($b ^ ord($key[$i % $keyLen]));
            }
            return bin2hex($result);
        } catch (\Exception $e) {
            return null;
        }
    }
}
