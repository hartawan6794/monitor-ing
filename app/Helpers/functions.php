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
if (! function_exists('inventoryTranstypes')) {
    /**
     * Get the mapping of Acosys inventory transtypes.
     *
     * @param int|null $id If provided, returns the label for the specific ID.
     * @return array|string
     */
    function inventoryTranstypes($id = null)
    {
        $labels = [
            1  => 'Pembelian',
            2  => 'Retur Pembelian',
            3  => 'Penjualan',
            4  => 'Retur Penjualan',
            5  => 'Pemindahan / Transfer',
            6  => 'Penyesuaian Masuk',
            7  => 'Penyesuaian Keluar',
            8  => 'Assembling',
            9  => 'Disassembling',
            10 => 'Order Penjualan',
            11 => 'Hadiah Pembelian',
            12 => 'Hadiah Penjualan',
            13 => 'Retur Pembelian (Hadiah)',
            14 => 'Retur Penjualan (Hadiah)',
            15 => 'Order Penjualan (Hadiah)',
            16 => 'Penerimaan Barang',
            17 => 'Pengiriman Barang',
            18 => 'Paket Penjualan',
            19 => 'Pembelian Konsinyasi',
            20 => 'Retur Pembelian Konsinyasi',
            21 => 'Pemakaian Barang',
            22 => 'Lain-lain',
        ];

        if ($id !== null) {
            return $labels[$id] ?? "Lainnya ($id)";
        }

        return $labels;
    }
}
