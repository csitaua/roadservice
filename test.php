<?php

$simple_string = "Welcome to GeeksforGeeks\n";
echo "Original String: " . $simple_string;
$ciphering = "AES-256-CTR";
$iv_length = openssl_cipher_iv_length($ciphering);
$options = 0;
$encryption_iv = '1234567891011121';
$encryption_key = "GeeksforGeeks";
$encryption = openssl_encrypt($simple_string, $ciphering,
            $encryption_key);
echo "Encrypted String: " . $encryption . "\n";
$decryption_iv = '1234567891011121';
$decryption_key = "GeeksforGeeks";
$decryption=openssl_decrypt ($encryption, $ciphering,
        $decryption_key);
echo "Decrypted String: " . $decryption;
?>
